<?php
namespace Hideyo\Repositories;
 
use Hideyo\Models\User;
use Hash;
use Validator;
use Config;
use Mail;
use Lang;

class UserRepository  extends BaseRepository implements UserRepositoryInterface
{


    protected $model;
    protected $validator;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * The validation rules for the model.
     *
     * @param  integer  $userId id attribute model    
     * @return array
     */
    private function rules($userId = false, $attributes = false)
    {
        $rules = array(
            'email'         => 'required|between:4,65|unique_with:'.$this->model->getTable(),
            'username'      => 'required|between:4,65|unique_with:'.$this->model->getTable()
        );
        
        if ($userId) {
            $rules['email']     =   $rules['email'].', '.$userId.' = id';
            $rules['username']  =   $rules['email'].', '.$userId.' = id';
        }

        return $rules;
    }

    public function getValidator()
    {
        return $this->validator;
    }

    public function selectAll()
    {
        return $this->model->all();
    }
    
    public function find($userId)
    {
        return $this->model->find($userId);
    }

    /**
     * Signup a new account with the given parameters
     *
     * @param  array $input Array containing 'username', 'email' and 'password'.
     *
     * @return  User User object that may or may not be saved successfully. Check the id to make sure.
     */
    public function signup($input)
    {
        $validator = Validator::make($input, $this->rules());

        if ($validator->fails()) {
            return $validator;
        }

        $this->model->username = array_get($input, 'username');
        $this->model->email    = array_get($input, 'email');
        $this->model->password = Hash::make(array_get($input, 'password'));
        $this->model->confirmed = 0;
        $this->model->language_id    = array_get($input, 'language_id');
        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        //$this->model->password_confirmation = array_get($input, 'password_confirmation');

        // Generate a random confirmation code
        $this->model->confirmation_code     = md5(uniqid(mt_rand(), true));
        $this->model->selected_shop_id    = array_get($input, 'selected_shop_id');

        // Save if valid. Password field will be hashed before save

        $this->model->save();

        if ($this->model->id) {
            // $role = $input['role'];
            // $roles = $this->model->roles;
            // $this->model->detachAllRoles($roles);
            // $this->model->attachRole( $role ); // Parameter can be an Role object, array or id.

            if (Config::get('confide::signup_email')) {
                $user = $this->model;
                Mail::queueOn(
                    Config::get('confide::email_queue'),
                    Config::get('confide::email_account_confirmation'),
                    compact('user'),
                    function ($message) use ($user) {
                        $message
                            ->to($user->email, $user->username)
                            ->subject(Lang::get('confide::confide.email.account_confirmation.subject'));
                    }
                );
            }
        }
  
        return $this->model;
    }

    public function updateProfileById(array $attributes, $avatar, $userId)
    {
        $this->model = $this->find($userId);
        if ($this->validator->validate($this->model, 'update')) {
            return $this->updateProfileEntity($attributes, $avatar);
        }

        return false;
    }

    public function updateShopProfileById($shop, $userId)
    {
        $this->model = $this->find($userId);

        if ($this->model->company_id == $shop->company_id) {
            $this->model->selected_shop_id = $shop->id;
            $this->model->save();
        }

        return true;
    }

    public function updateProfileEntity(array $attributes = array(), $avatar)
    {
        if (count($attributes) > 0) {
            $this->model->username = array_get($attributes, 'username');
  
            $this->model->selected_shop_id    = array_get($attributes, 'selected_shop_id');
            $this->model->email    = array_get($attributes, 'email');
            $this->model->language_id    = array_get($attributes, 'language_id');
            $this->model->save();

            return $this->model;
        }
    }

    public function updateById(array $attributes, $avatar, $userId)
    {

        $validator = Validator::make($attributes, $this->rules($userId));

        if ($validator->fails()) {
            return $validator;
        }


        $this->model = $this->find($userId);
        return $this->updateEntity($attributes, $avatar);
    }

    public function updateEntity(array $attributes = array())
    {
        if (count($attributes) > 0) {
            $this->model->username = array_get($attributes, 'username');
            $this->model->email    = array_get($attributes, 'email');
            $this->model->password = Hash::make(array_get($attributes, 'password'));
            $this->model->selected_shop_id    = array_get($attributes, 'selected_shop_id');
            $this->model->confirmed = array_get($attributes, 'confirmed');
            $this->model->save();
        }
   
        return $this->model;
    }

    /**
     * Attempts to login with the given credentials.
     *
     * @param  array $input Array containing the credentials (email/username and password)
     *
     * @return  boolean Success?
     */
    public function login($input)
    {
        if (! isset($input['password'])) {
            $input['password'] = null;
        }

        return \Confide::logAttempt($input, Config::get('confide::signup_confirm'));
    }

    /**
     * Checks if the credentials has been throttled by too
     * much failed login attempts
     *
     * @param  array $credentials Array containing the credentials (email/username and password)
     *
     * @return  boolean Is throttled
     */
    public function isThrottled($input)
    {
        return \Confide::isThrottled($input);
    }

    /**
     * Checks if the given credentials correponds to a user that exists but
     * is not confirmed
     *
     * @param  array $credentials Array containing the credentials (email/username and password)
     *
     * @return  boolean Exists and is not confirmed?
     */
    public function existsButNotConfirmed($input)
    {
        $user = \Confide::getUserByEmailOrUsername($input);

        if ($user) {
            $correctPassword = Hash::check(
                isset($input['password']) ? $input['password'] : false,
                $user->password
            );

            return (! $user->confirmed && $correctPassword);
        }
    }

    /**
     * Resets a password of a user. The $input['token'] will tell which user.
     *
     * @param  array $input Array containing 'token', 'password' and 'password_confirmation' keys.
     *
     * @return  boolean Success
     */
    public function resetPassword($input)
    {
        $result = false;
        $user   = Confide::userByResetPasswordToken($input['token']);

        if ($user) {
            $user->password              = $input['password'];
            $user->password_confirmation = $input['password_confirmation'];
            $result = $this->save($user);
        }

        // If result is positive, destroy token
        if ($result) {
            Confide::destroyForgotPasswordToken($input['token']);
        }

        return $result;
    }

    /**
     * Simply saves the given instance
     *
     * @param  User $instance
     *
     * @return  boolean Success
     */
    public function save(User $instance)
    {
        return $instance->save();
    }
}