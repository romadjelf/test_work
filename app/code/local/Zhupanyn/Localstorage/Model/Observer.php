<?php
class Zhupanyn_Localstorage_Model_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     * @throws Varien_Exception
     */
    public function addCookieCustomerLogin(Varien_Event_Observer $observer)
    {
        //$customer = $observer->getCustomer();
        //$cookieName = 'user_login'.$customer->store_id.$customer->website_id.$customer->entity_id;

        $cookie = Mage::getModel('core/cookie');
        //$store = $cookie->getStore();
        //$cookieName = 'user_login'.$store->store_id.$store->website_id;
        $cookieName = 'user_login';
        $cookie->set($cookieName, 1, null, null, null, null, false);

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     * @return $this
     * @throws Varien_Exception
     */
    public function addCookieCustomerLogout(Varien_Event_Observer $observer)
    {
        //$customer = $observer->getCustomer();
        //$cookieName = 'user_login'.$customer->store_id.$customer->website_id.$customer->entity_id;

        $cookie = Mage::getModel('core/cookie');
        //$store = $cookie->getStore();
        //$cookieName = 'user_login'.$store->store_id.$store->website_id;
        $cookieName = 'user_login';
        $cookie->set($cookieName, 0, null, null, null, null, false);

        return $this;
    }
}