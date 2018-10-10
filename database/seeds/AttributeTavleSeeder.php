<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Hideyo\Ecommerce\Framework\Models\Shop as Shop;
use Hideyo\Ecommerce\Framework\Models\Attribute as Attribute;
use Hideyo\Ecommerce\Framework\Models\AttributeGroup as AttributeGroup;
use Hideyo\Ecommerce\Framework\Models\ProductCategory as ProductCategory;

class AttributeTableSeeder extends Seeder
{
    public function run()
    {
        $attributeGroup = new AttributeGroup;

        DB::table($attributeGroup->getTable())->delete();
        $shop = Shop::where('title', '=', 'hideyo')->first();

        $attributeGroup->title = 'Size';   
        $attributeGroup->shop_id = $shop->id;
        $attributeGroup->save();

        $attribute = new Attribute;
        $attribute->value = 'S';   
        $attribute->attribute_group_id = $attributeGroup->id;
        $attribute->save();

        $attribute2 = new Attribute;
        $attribute2->value = 'M';   
        $attribute2->attribute_group_id = $attributeGroup->id;
        $attribute2->save();

        $attribute3 = new Attribute;
        $attribute3->value = 'L';   
        $attribute3->attribute_group_id = $attributeGroup->id;
        $attribute3->save();

        $attribute4 = new Attribute;
        $attribute4->value = 'XL';   
        $attribute4->attribute_group_id = $attributeGroup->id;
        $attribute4->save();

        $attributeGroup2 = new AttributeGroup;
        $attributeGroup2->title = 'Color';   
        $attributeGroup2->shop_id = $shop->id;
        $attributeGroup2->save();

        $attribute = new Attribute;
        $attribute->value = 'Blue';   
        $attribute->attribute_group_id = $attributeGroup2->id;
        $attribute->save();        

        $attribute = new Attribute;
        $attribute->value = 'Black';   
        $attribute->attribute_group_id = $attributeGroup2->id;
        $attribute->save();

        $attribute = new Attribute;
        $attribute->value = 'White';   
        $attribute->attribute_group_id = $attributeGroup2->id;
        $attribute->save();

        $attribute = new Attribute;
        $attribute->value = 'Yellow';   
        $attribute->attribute_group_id = $attributeGroup2->id;
        $attribute->save();
    }
}
