<?php namespace Syscover\Market\Services;

use Illuminate\Support\Facades\Log;
use PayPal\Api\FlowConfig;
use PayPal\Api\InputFields;
use PayPal\Api\Presentation;
use PayPal\Api\WebProfile;

class PayPalWebProfileService
{
    public static function get()
    {
        $response = null;

        try
        {
            $response = WebProfile::get_list(PayPalCoreService::getApiContext());
        }
        catch (\Exception $e)
        {
            Log::warning($e->getMessage());
        }

        return $response;
    }

    public static function find(string $id)
    {
        $webProfile = null;

        try
        {
            $webProfile = WebProfile::get($id, PayPalCoreService::getApiContext());
        }
        catch (\Exception $e)
        {
            Log::warning($e->getMessage());
        }

        return $webProfile;
    }

    public static function create(array $payload)
    {
        self::checkCreate($payload);
        $webProfile = self::createWebProfile($payload);

        $request                = clone $webProfile;
        $createProfileResponse  = null;

        try
        {
            $createProfileResponse = $webProfile->create(PayPalCoreService::getApiContext());
        }
        catch (\Exception $e)
        {
            Log::warning($e->getMessage());
        }

        return $createProfileResponse;
    }

    public static function update(array $payload)
    {
        self::checkUpdate($payload);
        $webProfile = self::createWebProfile($payload);

        try
        {
            if ($webProfile->update(PayPalCoreService::getApiContext()))
            {
                $webProfile = self::find($webProfile->getId());
            }
        }
        catch (\Exception $e)
        {
            Log::warning($e->getMessage());
        }

        return $webProfile;
    }

    public static function delete(string $id)
    {
        // get web profile
        $profile = self::find($id);

        // delete web profile
        $webProfile = new WebProfile();
        $webProfile->setId($id);

        try
        {
            $webProfile->delete(PayPalCoreService::getApiContext());
        }
        catch (\Exception $e)
        {
            Log::warning($e->getMessage());
        }

        return $profile;
    }

    private static function checkCreate($payload)
    {
        if(empty($payload['name']))                                     throw new \Exception('You have to define a name field to create a payment method');
        if(empty($payload['temporary']))                                throw new \Exception('You have to define a temporary field to create a payment method');

        if(empty($payload['presentation']['logo_image']))               throw new \Exception('You have to define a presentation.logo_image field to create a PayPal web profile');
        if(empty($payload['presentation']['brand_name']))               throw new \Exception('You have to define a presentation.brand_name field to create a PayPal web profile');
        if(empty($payload['presentation']['locale_code']))              throw new \Exception('You have to define a presentation.locale_code field to create a PayPal web profile');
        if(empty($payload['presentation']['return_url_label']))         throw new \Exception('You have to define a presentation.return_url_label field to create a PayPal web profile');
        if(empty($payload['presentation']['note_to_seller_label']))     throw new \Exception('You have to define a presentation.note_to_seller_label field to create a PayPal web profile');

        if(empty($payload['flow_config']['landing_page_type']))         throw new \Exception('You have to define a flow_config.landing_page_type field to create a PayPal web profile');
        if(empty($payload['flow_config']['bank_txn_pending_url']))      throw new \Exception('You have to define a flow_config.bank_txn_pending_url field to create a PayPal web profile');
        if(empty($payload['flow_config']['user_action']))               throw new \Exception('You have to define a flow_config.user_action field to create a PayPal web profile');
        if(empty($payload['flow_config']['return_uri_http_method']))    throw new \Exception('You have to define a flow_config.return_uri_http_method field to create a PayPal web profile');

        if(empty($payload['input_fields']['allow_note']))               throw new \Exception('You have to define a input_fields.allow_note field to create a PayPal web profile');
        if(empty($payload['input_fields']['no_shipping']))              throw new \Exception('You have to define a input_fields.no_shipping field to create a PayPal web profile');
        if(empty($payload['input_fields']['address_override']))         throw new \Exception('You have to define a input_fields.address_override field to create a PayPal web profile');
    }

    private static function checkUpdate($payload)
    {
        if(empty($payload['id']))                                       throw new \Exception('You have to define a id field to update a PayPal web profile');
        if(empty($payload['name']))                                     throw new \Exception('You have to define a name field to update a payment method');
        if(empty($payload['temporary']))                                throw new \Exception('You have to define a temporary field to update a payment method');

        if(empty($payload['presentation']['logo_image']))               throw new \Exception('You have to define a presentation.logo_image field to update a PayPal web profile');
        if(empty($payload['presentation']['brand_name']))               throw new \Exception('You have to define a presentation.brand_name field to update a PayPal web profile');
        if(empty($payload['presentation']['locale_code']))              throw new \Exception('You have to define a presentation.locale_code field to update a PayPal web profile');
        if(empty($payload['presentation']['return_url_label']))         throw new \Exception('You have to define a presentation.return_url_label field to update a PayPal web profile');
        if(empty($payload['presentation']['note_to_seller_label']))     throw new \Exception('You have to define a presentation.note_to_seller_label field to update a PayPal web profile');

        if(empty($payload['flow_config']['landing_page_type']))         throw new \Exception('You have to define a flow_config.landing_page_type field to update a PayPal web profile');
        if(empty($payload['flow_config']['bank_txn_pending_url']))      throw new \Exception('You have to define a flow_config.bank_txn_pending_url field to update a PayPal web profile');
        if(empty($payload['flow_config']['user_action']))               throw new \Exception('You have to define a flow_config.user_action field to update a PayPal web profile');
        if(empty($payload['flow_config']['return_uri_http_method']))    throw new \Exception('You have to define a flow_config.return_uri_http_method field to update a PayPal web profile');

        if(empty($payload['input_fields']['allow_note']))               throw new \Exception('You have to define a input_fields.allow_note field to update a PayPal web profile');
        if(empty($payload['input_fields']['no_shipping']))              throw new \Exception('You have to define a input_fields.no_shipping field to update a PayPal web profile');
        if(empty($payload['input_fields']['address_override']))         throw new \Exception('You have to define a input_fields.address_override field to update a PayPal web profile');
    }

    private static function createWebProfile(array $payload)
    {
        // ### Create Web Profile
        // Use the /web-profiles resource to create seamless payment experience profiles. See the payment experience overview for further information about using the /payment resource to create the PayPal payment and pass the experience_profile_id.
        // Documentation available at https://developer.paypal.com/webapps/developer/docs/api/#create-a-web-experience-profile

        // Lets create an instance of FlowConfig and add
        // landing page type information
        $flowConfig = new FlowConfig();

        // Type of PayPal page to be displayed when a user lands on the PayPal site for checkout.
        // Allowed values: Billing or Login. When set to Billing, the Non-PayPal account landing page is used. When set to Login, the PayPal account login landing page is used.
        $flowConfig->setLandingPageType($payload['flow_config']['landing_page_type']);

        // The URL on the merchant site for transferring to after a bank transfer payment.
        $flowConfig->setBankTxnPendingUrl($payload['flow_config']['bank_txn_pending_url']);

        // When set to "commit", the buyer is shown an amount, and the button text will read "Pay Now" on the checkout page.
        // Allowed values: continue or commit
        $flowConfig->setUserAction($payload['flow_config']['user_action']);

        // Defines the HTTP method to use to redirect the user to a return URL. A valid value is GET or POST.
        $flowConfig->setReturnUriHttpMethod($payload['flow_config']['return_uri_http_method']);


        // Parameters for style and presentation.
        $presentation = new Presentation();
        $presentation
            // A URL to logo image. Allowed vaues: .gif, .jpg, or .png.
            ->setLogoImage($payload['presentation']['logo_image'])
            //	A label that overrides the business name in the PayPal account on the PayPal pages.
            ->setBrandName($payload['presentation']['brand_name'])
            //  Locale of pages displayed by PayPal payment experience.
            ->setLocaleCode($payload['presentation']['locale_code'])
            // A label to use as hypertext for the return to merchant link.
            ->setReturnUrlLabel($payload['presentation']['return_url_label'])
            // A label to use as the title for the note to seller field. Used only when allow_note is 1.
            ->setNoteToSellerLabel($payload['presentation']['note_to_seller_label']);


        // Parameters for input fields customization.
        $inputFields = new InputFields();

        // Enables the buyer to enter a note to the merchant on the PayPal page during checkout.
        $inputFields->setAllowNote($payload['input_fields']['allow_note'])
            // Determines whether or not PayPal displays shipping address fields on the experience pages.
            // Allowed values: 0, 1, or 2.
            // When set to 0, PayPal displays the shipping address on the PayPal pages.
            // When set to 1, PayPal does not display shipping address fields whatsoever.
            // When set to 2, if you do not pass the shipping address, PayPal obtains it from the buyer’s account profile.
            // For digital goods, this field is required, and you must set it to 1.
            ->setNoShipping((int) $payload['input_fields']['no_shipping'])

            // Determines whether or not the PayPal pages should display the shipping address and not the shipping address on file with PayPal for this buyer.
            // Displaying the PayPal street address on file does not allow the buyer to edit that address.
            // Allowed values: 0 or 1.
            // When set to 0, the PayPal pages should not display the shipping address.
            // When set to 1, the PayPal pages should display the shipping address.
            ->setAddressOverride((int) $payload['input_fields']['address_override']);

        // #### Payment Web experience profile resource
        $webProfile = new WebProfile();

        // Set Id to update web profile
        if(isset($payload['id']))
        {
            $webProfile->setId($payload['id']);
        }

        // Name of the web experience profile. Required. Must be unique
        $webProfile->setName($payload['name'])
            // Parameters for flow configuration.
            ->setFlowConfig($flowConfig)
            // Parameters for style and presentation.
            ->setPresentation($presentation)
            // Parameters for input field customization.
            ->setInputFields($inputFields)
            // Indicates whether the profile persists for three hours or permanently. Set to false to persist the profile permanently. Set to true to persist the profile for three hours
            ->setTemporary($payload['temporary']);

        return $webProfile;
    }
}