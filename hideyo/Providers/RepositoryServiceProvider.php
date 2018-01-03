<?php

namespace Hideyo\Providers;

use Illuminate\Support\ServiceProvider;

use Hideyo\Repositories\ShopRepositoryInterface;
use Hideyo\Repositories\ShopRepository;
use Hideyo\Repositories\ProductCategoryRepositoryInterface;
use Hideyo\Repositories\ProductCategoryRepository;
use Hideyo\Repositories\BrandRepositoryInterface;
use Hideyo\Repositories\BrandRepository;
use Hideyo\Repositories\ProductRepositoryInterface;
use Hideyo\Repositories\ProductRepository;
use Hideyo\Repositories\ProductTagGroupRepositoryInterface;
use Hideyo\Repositories\ProductTagGroupRepository;
use Hideyo\Repositories\ExtraFieldRepositoryInterface;
use Hideyo\Repositories\ExtraFieldRepository;
use Hideyo\Repositories\AttributeGroupRepositoryInterface;
use Hideyo\Repositories\AttributeGroupRepository;
use Hideyo\Repositories\AttributeRepositoryInterface;
use Hideyo\Repositories\AttributeRepository;
use Hideyo\Repositories\ProductCombinationRepositoryInterface;
use Hideyo\Repositories\ProductCombinationRepository;
use Hideyo\Repositories\ProductAmountOptionRepositoryInterface;
use Hideyo\Repositories\ProductAmountOptionRepository;
use Hideyo\Repositories\ProductAmountSeriesRepositoryInterface;
use Hideyo\Repositories\ProductAmountSeriesRepository;
use Hideyo\Repositories\ProductRelatedProductRepositoryInterface;
use Hideyo\Repositories\ProductRelatedProductRepository;
use Hideyo\Repositories\RedirectRepositoryInterface;
use Hideyo\Repositories\RedirectRepository;
use Hideyo\Repositories\ProductExtraFieldValueRepositoryInterface;
use Hideyo\Repositories\ProductExtraFieldValueRepository;
use Hideyo\Repositories\OrderRepositoryInterface;
use Hideyo\Repositories\OrderRepository;
use Hideyo\Repositories\OrderAddressRepositoryInterface;
use Hideyo\Repositories\OrderAddressRepository;
use Hideyo\Repositories\OrderStatusRepositoryInterface;
use Hideyo\Repositories\OrderStatusRepository;
use Hideyo\Repositories\OrderStatusEmailTemplateRepositoryInterface;
use Hideyo\Repositories\OrderStatusEmailTemplateRepository;
use Hideyo\Repositories\CartRepositoryInterface;
use Hideyo\Repositories\CartRepository;
use Hideyo\Repositories\InvoiceRepositoryInterface;
use Hideyo\Repositories\InvoiceRepository;
use Hideyo\Repositories\InvoiceAddressRepositoryInterface;
use Hideyo\Repositories\InvoiceAddressRepository;
use Hideyo\Repositories\SendingMethodRepositoryInterface;
use Hideyo\Repositories\SendingMethodRepository;
use Hideyo\Repositories\SendingPaymentMethodRelatedRepositoryInterface;
use Hideyo\Repositories\SendingPaymentMethodRelatedRepository;
use Hideyo\Repositories\PaymentMethodRepositoryInterface;
use Hideyo\Repositories\PaymentMethodRepository;
use Hideyo\Repositories\CouponRepositoryInterface;
use Hideyo\Repositories\CouponRepository;
use Hideyo\Repositories\TaxRateRepositoryInterface;
use Hideyo\Repositories\TaxRateRepository;
use Hideyo\Repositories\GeneralSettingRepositoryInterface;
use Hideyo\Repositories\GeneralSettingRepository;
use Hideyo\Repositories\ClientRepositoryInterface;
use Hideyo\Repositories\ClientRepository;
use Hideyo\Repositories\ClientAddressRepositoryInterface;
use Hideyo\Repositories\ClientAddressRepository;
use Hideyo\Repositories\NewsRepositoryInterface;
use Hideyo\Repositories\NewsRepository;
use Hideyo\Repositories\ContentRepositoryInterface;
use Hideyo\Repositories\ContentRepository;
use Hideyo\Repositories\HtmlBlockRepositoryInterface;
use Hideyo\Repositories\HtmlBlockRepository;
use Hideyo\Repositories\FaqItemRepositoryInterface;
use Hideyo\Repositories\FaqItemRepository;
use Hideyo\Repositories\UserRepositoryInterface;
use Hideyo\Repositories\UserRepository;
use Hideyo\Repositories\ExceptionRepositoryInterface;
use Hideyo\Repositories\ExceptionRepository;

class RepositoryServiceProvider extends ServiceProvider {
    
    /**
     * Note: please keep logic in this repository. Put logic not in models,
     * Information about models in Laravel: http://laravel.com/docs/5.1/eloquent
     * @author     Matthijs Neijenhuijs <matthijs@dutchbridge.nl>
     * @copyright  DutchBridge - dont share/steel!
     */
    
    public function register()
    {
        $this->app->singleton(ShopRepositsoryInterface::class, ShopRepository::class);
        $this->app->singleton(ProductCombinationRepositoryInterface::class, ProductCombinationRepository::class);
        $this->app->singleton(AttributeRepositoryInterface::class, AttributeRepository::class);
        $this->app->singleton(AttributeGroupRepositoryInterface::class, AttributeGroupRepository::class);
        $this->app->singleton(LanguageRepositoryInterface::class, LanguageRepository::class);
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->singleton(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->singleton(ProductImageRepositoryInterface::class, ProductImageRepository::class);
        $this->app->singleton(ProductRelatedProductRepositoryInterface::class, ProductRelatedProductRepository::class);
        $this->app->singleton(ProductExtraFieldValueRepositoryInterface::class, ProductExtraFieldValueRepository::class);
        $this->app->singleton(ProductVariationRepositoryInterface::class, ProductVariationRepository::class);
        $this->app->singleton(ExtraFieldRepositoryInterface::class, ExtraFieldRepository::class);
        $this->app->singleton(ExtraFieldDefaultValueRepositoryInterface::class, ExtraFieldDefaultValueRepository::class);
        $this->app->singleton(ExceptionRepositoryInterface::class, ExceptionRepository::class);
        $this->app->singleton(CouponRepositoryInterface::class, CouponRepository::class);
        $this->app->singleton(GiftVoucherRepositoryInterface::class, GiftVoucherRepository::class);
        $this->app->singleton(DiscountRepositoryInterface::class, DiscountRepository::class);
        $this->app->singleton(CouponGroupRepositoryInterface::class, CouponGroupRepository::class);
        $this->app->singleton(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->singleton(WholesaleClientRepositoryInterface::class, WholesaleClientRepository::class);
        $this->app->singleton(ClientAddressRepositoryInterface::class, ClientAddressRepository::class);
        $this->app->singleton(WholesaleClientAddressRepositoryInterface::class, WholesaleClientAddressRepository::class);
        $this->app->singleton(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->singleton(ProductCategoryRepositoryInterface::class, ProductCategoryRepository::class);
        $this->app->singleton(ContentRepositoryInterface::class, ContentRepository::class);
        $this->app->singleton(ContentImageRepositoryInterface::class, ContentImageRepository::class);
        $this->app->singleton(HtmlBlockRepositoryInterface::class, HtmlBlockRepository::class);
        $this->app->singleton(ShopRepositoryInterface::class, ShopRepository::class);
        $this->app->singleton(UserLogRepositoryInterface::class, UserLogRepository::class);
        $this->app->singleton(ProductCategoryImageRepositoryInterface::class, ProductCategoryImageRepository::class);
        $this->app->singleton(TaxRateRepositoryInterface::class, TaxRateRepository::class);
        $this->app->singleton(ProductVariationTypeRepositoryInterface::class, ProductVariationTypeRepository::class);
        $this->app->singleton(PaymentMethodRepositoryInterface::class, PaymentMethodRepository::class);
        $this->app->singleton(SendingMethodRepositoryInterface::class, SendingMethodRepository::class);
        $this->app->singleton(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->singleton(OrderAddressRepositoryInterface::class, OrderAddressRepository::class);
        $this->app->singleton(OrderStatusEmailTemplateRepositoryInterface::class, OrderStatusEmailTemplateRepository::class);
        $this->app->singleton(OrderStatusRepositoryInterface::class, OrderStatusRepository::class);
        $this->app->singleton(OrderPaymentLogRepositoryInterface::class, OrderPaymentLogRepository::class);
        $this->app->singleton(CartRepositoryInterface::class, CartRepository::class);
        $this->app->singleton(SendingPaymentMethodRelatedRepositoryInterface::class, SendingPaymentMethodRelatedRepository::class);
        $this->app->singleton(CollectionRepositoryInterface::class, CollectionRepository::class);
        $this->app->singleton(RedirectRepositoryInterface::class, RedirectRepository::class);
        $this->app->singleton(InvoiceRepositoryInterface::class, InvoiceRepository::class);
        $this->app->singleton(InvoiceAddressRepositoryInterface::class, InvoiceAddressRepository::class);
        $this->app->singleton(ProductAmountOptionRepositoryInterface::class, ProductAmountOptionRepository::class);
        $this->app->singleton(ProductAmountSeriesRepositoryInterface::class, ProductAmountSeriesRepository::class);
        $this->app->singleton(GeneralSettingRepositoryInterface::class, GeneralSettingRepository::class);
        $this->app->singleton(FaqItemRepositoryInterface::class, FaqItemRepository::class);
        $this->app->singleton(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->singleton(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->singleton(ProductTagGroupRepositoryInterface::class, ProductTagGroupRepository::class);
        $this->app->singleton(ExceptionRepositoryInterface::class, ExceptionRepository::class);

    }
}