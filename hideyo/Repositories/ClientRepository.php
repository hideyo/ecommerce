<?php
namespace Hideyo\Repositories;

use Hideyo\Models\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Hideyo\Repositories\ShopRepositoryInterface;
use Hideyo\Repositories\ClientAddressRepositoryInterface;
use Mail;
use Config;
use Carbon\Carbon;
use Validator;
use Auth;
use Hash;

class ClientRepository extends BaseRepository implements ClientRepositoryInterface
{

    protected $model;

    public function __construct(
        Client $model, 
        ShopRepositoryInterface $shop, 
        ClientAddressRepositoryInterface $clientAddress)
    {
        $this->model = $model;
        $this->shop = $shop;
        $this->clientAddress = $clientAddress;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $clientId id attribute model    
     * @return array
     */
    private function rules($clientId = false)
    {
        if ($clientId) {
            $rules = array(
                'email' => 'required|email|unique_with:client, shop_id'
            );
        } else {
            $rules = array(
                'email' => 'required|email|unique_with:'.$this->model->getTable().', shop_id',
                'gender' => 'required',
                'firstname' => 'required',
                'lastname' => 'required',
                'street' => 'required',
                'housenumber' => 'required|integer',
                'zipcode' => 'required',
                'city' => 'required',
                'country' => 'required'
            );
        }


        if ($clientId) {
            $rules['email'] =   'required|email|unique_with:'.$this->model->getTable().', shop_id, '.$clientId.' = id';
        }

        return $rules;
    }

    public function create(array $attributes)
    {
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $validator = Validator::make($attributes, $this->rules());

        if ($validator->fails()) {
            return $validator;
        }

        $attributes['password'] = Hash::make($attributes['password']);
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;
        $this->model->fill($attributes);
        $this->model->save();
        $clientAddress = $this->clientAddress->create($attributes, $this->model->id);
        $new['delivery_client_address_id'] = $clientAddress->id;
        $new['bill_client_address_id'] = $clientAddress->id;
        $this->model->fill($new);
        $this->model->save();
        return $this->model;
    }

    public function setAccountChange($user, $attributes, $shopId)
    {
        $checkEmailExist = $this->model
        ->where('shop_id', '=', $shopId)
        ->where('email', '=', $attributes['email'])
        ->get()
        ->first();

        if ($checkEmailExist) {
            return false;
        }

        $this->model = $this->find($user->id);

        if ($this->model) {
            $newAttributes['new_email'] = $attributes['email'];
            $newAttributes['new_password'] = Hash::make($attributes['password']);
            $newAttributes['confirmation_code'] = md5(uniqid(mt_rand(), true));
            return $this->updateEntity($newAttributes);
        }
    }

    public function updateById(array $attributes, $clientId)
    {
        $this->model = $this->find($clientId);
        $attributes['shop_id'] = auth('hideyobackend')->user()->selected_shop_id;
        $attributes['modified_by_user_id'] = auth('hideyobackend')->user()->id;

        $validator = Validator::make($attributes, $this->rules($clientId));

        if ($validator->fails()) {
            return $validator;
        }

        unset($attributes['password']);

        if ($attributes['password']) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        return $this->updateEntity($attributes);
    }

    public function selectAll()
    {
        return $this->model->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->get();
    }

    public function selectAllByBillClientAddress()
    {
        return $this->model->selectRaw('CONCAT(client_address.firstname, " ", client_address.lastname) as fullname, client_address.*, client.id')
        ->leftJoin('client_address', 'client.bill_client_address_id', '=', 'client_address.id')->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)
        ->get();
    }

    public function findByEmail($email, $shopId)
    {
        $client = $this->model->where('shop_id', '=', $shopId)->where('email', '=', $email)->get()->first();
        return $client;
    }


    public function validateRegister(array $attributes, $shopId)
    {
        $client = $this->model->where('shop_id', '=', $shopId)->where('email', '=', $attributes['email'])->get()->first();

        if ($client) {
            return false;
        }

        return true;
    }

    public function validateRegisterNoAccount(array $attributes, $shopId)
    {
        $client = $this->model->where('shop_id', '=', $shopId)->where('email', '=', $attributes['email'])->get()->first();

        if ($client) {
            return false;
        }

        return true;
    }

    function selectOneByShopIdAndId($shopId, $clientId)
    {
        $result = $this->model->with(array('clientAddress', 'clientDeliveryAddress', 'clientBillAddress'))->where('shop_id', '=', $shopId)->where('active', '=', 1)->where('id', '=', $clientId)->first();
        return $result;
    }

    function selectOneById($clientId)
    {
        $result = $this->model->with(array('clientAddress', 'clientDeliveryAddress', 'clientBillAddress'))->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->where('active', '=', 1)->where('id', '=', $clientId)->first();
        return $result;
    }

    function setBillOrDeliveryAddress($shopId, $clientId, $addressId, $type)
    {
        $this->model = $this->model->where('shop_id', '=', $shopId)->where('active', '=', 1)->where('id', '=', $clientId)->get()->first();
        
        if ($this->model) {
            if ($type == 'bill') {
                $attributes['bill_client_address_id'] = $addressId;
            } elseif ($type == 'delivery') {
                $attributes['delivery_client_address_id'] = $addressId;
            }
            
            return $this->updateEntity($attributes);
        }
        
        return false;
    }

    function confirm($code, $email, $shopId)
    {
        $this->model = $this->model->where('shop_id', '=', $shopId)->where('confirmation_code', '=', $code)->get()->first();

        if ($this->model) {
            $attributes['confirmed'] = 1;
            $attributes['active'] = 1;
            $attributes['confirmation_code'] = null;
            
            return $this->updateEntity($attributes);
        }
        
        return false;
    }


    function activate($clientId)
    {
        $this->model = $this->model->where('id', '=', $clientId)->get()->first();

        if ($this->model) {
            $attributes['confirmed'] = 1;
            $attributes['active'] = 1;
            $attributes['confirmation_code'] = null;
            
            return $this->updateEntity($attributes);
        }
        
        return false;
    }

    function deactivate($clientId)
    {
        $this->model = $this->model->where('id', '=', $clientId)->get()->first();

        if ($this->model) {
            $attributes['confirmed'] = 0;
            $attributes['active'] = 0;
            $attributes['confirmation_code'] = null;
            
            return $this->updateEntity($attributes);
        }
        
        return false;
    }

    function getConfirmationCodeByEmail($email, $shopId)
    {
        $result = array();
        $result['result'] = false;

        $this->model = $this->model->where('shop_id', '=', $shopId)->whereNotNull('account_created')->where('email', '=', $email)->get()->first();

        if ($this->model) {
            $attributes['confirmation_code'] = md5(uniqid(mt_rand(), true));
            return $this->updateEntity($attributes);
        }
        
        return false;
    }

    function validateConfirmationCodeByConfirmationCodeAndEmail($confirmationCode, $email, $shopId)
    {
        return $this->model = $this->model
        ->where('shop_id', '=', $shopId)
        ->where('email', '=', $email)
        ->whereNotNull('account_created')
        ->where('confirmation_code', '=', $confirmationCode)
        ->get()->first();
    }

    public function register(array $attributes, $shopId, $accountConfirmed = false)
    {
        $result = array();
        $result['result'] = false;

        $shop = $this->shop->find($shopId);

        $client = $this->model->where('shop_id', '=', $shopId)->where('email', '=', $attributes['email'])->get()->first();

        if ($client) {
            return false;
        }

        $attributes['shop_id'] = $shopId;
        $attributes['modified_by_user_id'] = null;

        $attributes['active'] = 0;
        $attributes['confirmed'] = 0;
        $attributes['confirmation_code'] = md5(uniqid(mt_rand(), true));
        $mailChimplistId = Config::get('mailchimp.consumerId');        

        if($accountConfirmed) {
            $attributes['confirmation_code'] = null;
            $attributes['active'] = 1;
            $attributes['confirmed'] = 1;
        }
        
        if (isset($attributes['password'])) {            
            $attributes['password'] = Hash::make($attributes['password']);
            $attributes['account_created'] = Carbon::now()->toDateTimeString();
        }

        $this->model->fill($attributes);
        $this->model->save();

        $clientAddress = $this->clientAddress->createByClient($attributes, $this->model->id);
        $new['delivery_client_address_id'] = $clientAddress->id;
        $new['bill_client_address_id'] = $clientAddress->id;
        $this->model->fill($new);
        $this->model->save();
        return $this->model;
    }

    public function resetAccount($code, $newEmail, $shopId)
    {

        $checkEmailExist = $this->model->where('shop_id', '=', $shopId)->where('email', '=', $newEmail)->get()->first();

        if ($checkEmailExist) {
            return false;
        }

        $check = $this->model->whereNotNull('account_created')->where('shop_id', '=', $shopId)->where('new_email', '=', $newEmail)->where('confirmation_code', '=', $code)->get()->first();

        if ($check) {
            $newAttributes['email'] = $check->new_email;
            $newAttributes['password'] = $check->new_password;
            $newAttributes['confirmed'] = 1;
            $newAttributes['active'] = 1;
            $newAttributes['confirmation_code'] = null;
            $this->model = $this->find($check->id);
            return $this->updateEntity($newAttributes);
        }

        return false;
    }

    public function updateLastLogin($clientId)
    {
        $check = $this->model->where('id', '=', $clientId)->get()->first();

        if ($check) {
            $newAttributes['last_login'] = Carbon::now();
            $this->model = $this->find($check->id);
            return $this->updateEntity($newAttributes);
        }

        return false;
    }

    public function resetPasswordByConfirmationCodeAndEmail(array $attributes, $shopId)
    {
        $result = array();
        $result['result'] = false;

        $this->model = $this->model->whereNotNull('account_created')->where('shop_id', '=', $shopId)->where('email', '=', $attributes['email'])->where('confirmation_code', '=', $attributes['confirmation_code'])->get()->first();

        if ($this->model) {
            if ($attributes['password']) {
                $attributes['confirmed'] = 1;
                $attributes['active'] = 1;
                $attributes['confirmation_code'] = null;
                $attributes['password'] = Hash::make($attributes['password']);
            }

            $this->updateEntity($attributes);
            $result['result'] = true;
        } else {
            $result['errors'][] = 'message.error.email-with-code-not-exist';
        }

        return $result;
    }


    public function selectAllExport()
    {
        return $this->model->with(array('clientAddress', 'clientDeliveryAddress', 'clientBillAddress'))->whereNotNull('account_created')->where('active', '=', 1)->where('shop_id', '=', auth('hideyobackend')->user()->selected_shop_id)->get();
    }

    public static function encodePassword($password, $salt = 'foodeliciousnl', $count = 1000, $length = 32, $algorithm = 'sha1', $start = 16)
    {
        $hash = self::protectPassword($password, $salt, $count, $length, $algorithm, $start);

        return base64_encode($hash);
    }

    function editAddress($shopId, $clientId, $addressId, $attributes)
    {
        $address = $this->clientAddress->updateByIdAndShopId($shopId, $attributes, $clientId, $addressId);
    }

    public static function protectPassword($password, $salt, $count, $length, $algorithm = 'sha256', $start = 0)
    {
        $keyblock = $start + $length;                        // Key blocks to compute
        $derivedKey = '';                                    // Derived key

        // Create key
        for ($block=1; $block <= $keyblock; $block++) {
          // Initial hash for this block
            $ib = $hash = hash_hmac($algorithm, $salt . pack('N', $block), $password, true);

          // Perform block iterations
            for ($i=1; $i<$count; $i++) {
              // XOR each iterate
                $ib ^= ($hash = hash_hmac($algorithm, $hash, $password, true));
            }

            $derivedKey .= $ib;                                // Append iterated block
        }

        // Return derived key of correct length
        return substr($derivedKey, $start, $length);
    }
}
