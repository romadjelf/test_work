<?php
class Zhupanyn_Localstorage_Model_Observer
{
    public function add_cookie_customer_login(Varien_Event_Observer  $observer)
    {
        //$customer = $observer->getCustomer();
        //$cookie_name = 'user_login'.$customer->store_id.$customer->website_id.$customer->entity_id;

        $cookie = Mage::getModel('core/cookie');
        //$store = $cookie->getStore();
        //$cookie_name = 'user_login'.$store->store_id.$store->website_id;
        $cookie_name = 'user_login';
        $cookie->set($cookie_name, 1, null, null, null, null, false);

        return $this;
    }

    public function add_cookie_customer_logout(Varien_Event_Observer  $observer)
    {
        //$customer = $observer->getCustomer();
        //$cookie_name = 'user_login'.$customer->store_id.$customer->website_id.$customer->entity_id;

        $cookie = Mage::getModel('core/cookie');
        //$store = $cookie->getStore();
        //$cookie_name = 'user_login'.$store->store_id.$store->website_id;
        $cookie_name = 'user_login';
        $cookie->set($cookie_name, 0, null, null, null, null, false);

        return $this;
    }
}
?>