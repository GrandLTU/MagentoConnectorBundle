Cart and customer data synchronization
======================================

Synchronizing from Magento to ONGR is done via cookies. After every action with cart (adding or removing products)
a cookie is set with all the products in cart. Also after login, logout and customer information update another cookie
is updated.

Synchronization from ONGR to Magento is done via requests. More detailed information about that will come up later.

Setting up synchronization will require installing `MagentoSyncModule <https://github.com/ongr-io/MagentoSyncModule>`_
and a bit of configuration.

Step 1. Installing Magento Module
---------------------------------

Follow standard procedure for installing
`Magento Sync Module
<http://www.magentocommerce.com/wiki/7_-_modules_and_development/how_to_use_magentoconnect#editing_a_magento_community_extension>`_.
Then, if needed, set domain of ONGR project in ``system->configuration->ONGR SYNC->Sync Options``.
This setting is used for cookie domain. If it is left empty, current domain will be used.

Step 2. Configuring MagentoConnectorBundle
------------------------------------------

This bundle has two services to handle the synchronization with magento and they require some configuration to work
properly.

In your config file under ``ongr_magento`` you will need to set ``url`` of Magento store, ``es_manager`` and
``product_repository``.

Example configuration:

.. code-block:: yaml

    ongr_magento:
        store_id: 0
        shop_id: 1
        url: http://magento.ongr.dev
        es_manager: magento
        product_repository: ONGRMagentoConnectorBundle:ProductDocument
        cart_route: ongr_cart

..

Using the Customer service.
---------------------------

Customer service id is ``ongr_magento.sync.cart``. This service allows getting currently logged in customer information
and helps with login and logout functionality.

Login
~~~~~

Method ``getLoginUrl`` will return url to login page in Magento with return url of current location attached as
a parameter so that user is instantly redirected to back to ONGR project after successful login.

Logout
~~~~~~

Method ``getLogoutUrl`` will returl url to logout page in Magento with return url of
current location attached as a parameter. User will be instantly redirected back to current
location after following this url.

Getting Customer Data
~~~~~~~~~~~~~~~~~~~~~

Method ``getUserData`` will fetch customer data from cookie to a ParameterBag. Default Magento installation will
provide with the following fields:

* id
* website_id
* entity_id
* entity_type_id
* attribute_set_id
* email
* group_id
* increment_id
* store_id
* created_at
* updated_at
* is_active
* disable_auto_group_change
* created_in
* firstname
* lastname
* default_billing
* default_shipping

Using the Cart service.
-----------------------

Customer service id is ``ongr_magento.sync.cart``. This service allows adding, removing and viewing products in cart.

Manipulating products
~~~~~~~~~~~~~~~~~~~~~

Service holds products in associative array where key is product id and value is quantity. Contents can be set directly
with ``setCartContent`` method or added with ``addProduct`` method and removed with ``removeProduct`` method.
After updating cart you will need to sync cart with Magento by using getUpdateResponse method.
It will generate a redirect response with cart data to Magento, which,
after adding products, will redirect to ``ongr_cart`` route.

When redirecting Magento will create a list of products that were not added.
That list can be accessed by using the getErrorDocuments method.

Also ``getCheckoutUrl`` method will return url for checking out the products in magento.

Displaying products
~~~~~~~~~~~~~~~~~~~

Method ``getCartDocuments`` will get an array of product documents and quantities which can be used
for displaying purposes. Returned array format:

.. code-block:: php

    [
        ['document' => $document1, 'quantity' => $quantity1],
        ['document' => $document2, 'quantity' => $quantity2],
        ...
    ]

..

Example actions
---------------

.. code-block:: php

    /**
     * Displays cart contents.
     *
     * @Route("/cart")
     */
    public function cartAction()
    {
        return $this->render(
            'AcmeMagentoBundle::cart:html.twig',
            [
                'cart' => $this->getCart()->getCartDocuments(),
                'error' => $this->getCart()->getErrorDocuments(),
                'checkoutUrl' => $this->getCart()->getCheckoutUrl(),
            ]
        );
    }

..

.. code-block:: php

    /**
     * Adds product to cart and syncs cart with magento.
     *
     * @Route("/cart/add/{id}/{quantity}", defaults={"quantity" : 1})
     */
    public function addAction($id, $quantity)
    {
        return $this->getCart()->addProduct($id, $quantity)->getUpdateResponse();
    }

..

.. code-block:: php

    /**
     * Display user block.
     *
     * @Route("/customer")
     */
    public function customerAction()
    {
        return $this->render(
            'AcmeMagentoBundle::cart:html.twig',
            [
                'userData' => $this->getCustomer()->getUserData(),
                'cartCount' => count($this->getCart()),
                'logoutUrl' => $this->getCustomer()->getLogoutUrl(),
                'loginUrl' => $this->getCustomer()->getLoginUrl(),
            ]
        );
    }

..
