<?php
/**
* Since 2010 Webkul.
*
* NOTICE OF LICENSE
*
* All right is reserved,
* Please go through this link for complete license : https://store.webkul.com/license.html
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize this module for your
* needs please refer to https://store.webkul.com/customisation-guidelines/ for more information.
*
*  @author    Webkul IN <support@webkul.com>
*  @copyright since 2010 Webkul IN
*  @license   https://store.webkul.com/license.html
*/

class WebserviceSpecificManagementBookingCore Extends ObjectModel implements WebserviceSpecificManagementInterface
{
    protected $objOutput;
    protected $output;
    protected $wsObject;

    public $id;
    public $id_product;
    public $id_order;
    public $id_order_detail;
    public $id_cart;
    public $id_room;
    public $id_hotel;
    public $id_customer;
    public $booking_type;
    public $comment;
    public $check_in;
    public $check_out;
    public $date_from;
    public $date_to;
    public $total_price_tax_excl;
    public $total_price_tax_incl;
    public $total_paid_amount;
    public $is_back_order;
    public $id_status;
    public $is_refunded;
    public $is_cancelled;
    public $hotel_name;
    public $room_type_name;
    public $city;
    public $state;
    public $country;
    public $zipcode;
    public $phone;
    public $email;
    public $check_in_time;
    public $check_out_time;
    public $room_num;
    public $adults;
    public $children;
    public $child_ages;

    public const BOOKING_API_BOOKING_STATUS_NEW = 1;
    public const BOOKING_API_BOOKING_STATUS_COMPLETED = 2;
    public const BOOKING_API_BOOKING_STATUS_CANCELLED = 3;
    public const BOOKING_API_BOOKING_STATUS_REFUNDED = 4;

    public const BOOKING_API_PAYMENT_STATUS_COMPLETED = 1;
    public const BOOKING_API_PAYMENT_STATUS_PARTIAL = 2;
    public const BOOKING_API_PAYMENT_STATUS_AWATING = 3;

    public static $definition = array(
        'table' => 'htl_booking_detail',
        'primary' => 'booking_id',
        'fields' => array()
    );

    public $webserviceParameters = array(
        'objectsNodeName' => 'bookings',
        'objectNodeName' => 'booking',
        'fields' => array(
            'id_booking' => array('type' => self::TYPE_INT),
            'id_property' => array('type' => self::TYPE_INT, 'required' => true),
            'currency' => array('type' => self::TYPE_STRING),
            'booking_status' => array('type' => self::TYPE_INT, 'required' => true),
            'payment_status' => array('type' => self::TYPE_INT, 'required' => true),
            'source' => array('type' => self::TYPE_STRING),
            'booking_date' => array('type' => self::TYPE_DATE),
            'remark' => array('type' => self::TYPE_STRING),
        ),
        'associations' => array(
            'guest_detail' => array(
                'firstname' => array('type' => self::TYPE_STRING, 'required' => true),
                'lastname' => array('type' => self::TYPE_STRING, 'required' => true),
                'email' => array('type' => self::TYPE_STRING, 'required' => true),
                'phone' => array('type' => self::TYPE_STRING, 'required' => true),
                'address' => array('type' => self::TYPE_STRING),
                'city' => array('type' => self::TYPE_STRING),
                'zip' => array('type' => self::TYPE_STRING),
                'State_code' => array('type' => self::TYPE_STRING),
                'country_code' => array('type' => self::TYPE_STRING),
            ),
            'price_detial' => array(
                'total_paid' => array('type' => self::TYPE_FLOAT),
                'total_price_with_tax' => array('type' => self::TYPE_FLOAT),
                'total_tax' => array('type' => self::TYPE_FLOAT),
            ),
            'payment_detail' => array(
                'payment_type' => array('type' => self::TYPE_STRING),
                'payment_method' => array('type' => self::TYPE_STRING),
                'transaction_id' => array('type' => self::TYPE_STRING),
            ),
            'room_types' => array(
                'setter' => false,
                'resource' => 'room_type',
                'fields' => array(
                    'id_room_type' => array('type' => self::TYPE_INT, 'required' => true),
                    'checkin_date' => array('type' => self::TYPE_DATE, 'required' => true),
                    'checkout_date' => array('type' => self::TYPE_DATE, 'required' => true),
                    'total_price_with_tax' => array('type' => self::TYPE_FLOAT),
                    'total_tax' => array('type' => self::TYPE_FLOAT),
                    'total_rooms' => array('type' => self::TYPE_INT, 'required' => true),
                    'rooms' => array(
                        'resource' => 'room',
                        'fields' => array(
                            'adults' => array('type' => self::TYPE_INT),
                            'child' => array('type' => self::TYPE_INT),
                            'child_ages' => array(
                                'child_age' => array('type' => self::TYPE_INT)
                            ),
                            'total_price_with_tax' => array('type' => self::TYPE_INT),
                            'total_tax' => array('type' => self::TYPE_FLOAT),
                            'services' => array(
                                'resource' => 'service',
                                'fields' => array(
                                    'id_service' => array('type' => self::TYPE_INT),
                                    'name' => array('type' => self::TYPE_INT),
                                    // 'unit_price_with_tax' => array('type' => self::TYPE_INT),
                                    // 'unit_price_without_tax' => array('type' => self::TYPE_INT),
                                    // can be calculated using the total price.
                                    'total_price_with_tax' => array('type' => self::TYPE_INT),
                                    // 'total_tax' => array('type' => self::TYPE_INT),
                                    'quantity' => array('type' => self::TYPE_INT),
                                    'price_mode' => array('type' => self::TYPE_INT),
                                )
                            ),
                            'facilities' => array(
                                'resource' => 'facility',
                                'fields' => array(
                                    'id_option' => array('type' => self::TYPE_INT),
                                    'name' => array('type' => self::TYPE_INT),
                                    // 'unit_price_with_tax' => array('type' => self::TYPE_INT),
                                    // 'unit_price_without_tax' => array('type' => self::TYPE_INT),
                                    // can not be of multiple quantity
                                    'total_price_with_tax' => array('type' => self::TYPE_INT),
                                    // 'total_tax' => array('type' => self::TYPE_INT),
                                    'price_mode' => array('type' => self::TYPE_INT)
                                )
                            )
                        )
                    )
                ),
            )
        )
    );

    /**
     * @param WebserviceOutputBuilderCore $obj
     * @return WebserviceSpecificManagementInterface
     */
    public function setObjectOutput(WebserviceOutputBuilderCore $obj)
    {
        $this->objOutput = $obj;
        return $this;
    }

    public function setWsObject(WebserviceRequestCore $obj)
    {
        $this->wsObject = $obj;
        return $this;
    }

    public function getWsObject()
    {
        return $this->wsObject;
    }
    public function getObjectOutput()
    {
        return $this->objOutput;
    }

    public function setUrlSegment($segments)
    {
        $this->urlSegment = $segments;
        return $this;
    }

    public function getUrlSegment()
    {
        return $this->urlSegment;
    }

    public function getContent()
    {
        return $this->objOutput->getObjectRender()->overrideContent($this->output);
    }

    public function manage()
    {
        $this->context = Context::getContext();
        switch ($this->wsObject->method) {
            case 'GET':
                if ($this->wsObject->urlFragments['schema']) {
                    $object = new WebserviceSpecificManagementBooking();
                    $typeOfView = WebserviceOutputBuilder::VIEW_DETAILS;
                    $this->wsObject->objects = [];
                    $this->wsObject->objects[] = $object;
                    $this->wsObject->objects['empty'] = $object;
                    $this->wsObject->schemaToDisplay = $this->wsObject->urlFragments['schema'];

                    $this->output .= $this->objOutput->getContent(
                        $this->wsObject->objects,
                        $this->wsObject->schemaToDisplay,
                        $this->wsObject->fieldsToDisplay,
                        $this->wsObject->depth,
                        $typeOfView,
                        false
                    );
                } else {
                    if (isset($this->wsObject->urlSegment[1]) && $this->wsObject->urlSegment[1]) {
                        $this->getBookingDetails($this->wsObject->urlSegment[1]);
                    } else {
                        // throw new WebserviceException(
                        //     'Booking id is required',
                        //     array(404, 400)
                        // );
                    }
                }
            break;
            case 'POST':
                $inputData = $this->getRequestParams('booking');
                $associations = array();
                if (isset($inputData['associations'])) {
                    $associations = $inputData['associations'];
                    unset($inputData['associations']);
                }

                $inputData = array_merge($inputData, $associations);
                if ($this->validateBookingFields($inputData)) {
                    $this->createBooking($inputData);
                    $this->renderResponse();
                } else {
                    throw new WebserviceException(
                        $this->error_msg,
                        array(404, 400)
                    );
                }
            break;
            case 'PUT':
                // handle update here.
                $inputData = $this->getRequestParams('booking');
                $associations = array();
                if (isset($inputData['associations'])) {
                    $associations = $inputData['associations'];
                    unset($inputData['associations']);
                }

                $inputData = array_merge($inputData, $associations);
                // since post and put requires different data, validation is also different
                if ($this->validatePutRequestParams($inputData)) {
                    $this->handlePutRequest($inputData);
                    $this->renderResponse();
                } else {
                    throw new WebserviceException(
                        $this->error_msg,
                        array(404, 400)
                    );
                }

                break;
        }

        return $this->output;
    }

    public function renderResponse()
    {
        if (get_class($this->objOutput->getObjectRender()) == 'WebserviceOutputJSON') {
            $this->getResponseJson();
        } else {
            $this->getResponseXml();
        }
    }

    public function getResponseJson()
    {
        $this->output = json_encode($this->output);
        $this->output = preg_replace_callback("/\\\\u([a-f0-9]{4})/", function ($matches) {
            return iconv('UCS-4LE','UTF-8', pack('V', hexdec('U' . $matches[1])));
        }, $this->output);
    }

    public function getResponseXml()
    {
        if (is_array($this->output)) {
            $this->output = $this->renderOutputUsingArray($this->output);
        }
    }

    public function renderOutputUsingArray($response, $keyToIgnore = array(), $parentKey = '', $useEmpty = false)
    {
        $output = '';
        foreach ($response as $key => $res) {
            if (in_array($key, $keyToIgnore) && $key) {
                continue;
            }

            $currentKey = $key;
            if (gettype($key) == 'integer' || (int) $key) {
                $key = $parentKey;
            }

            if (is_array($res) && count($res)) {
                $output .= $this->renderHeader($key);
                $output .= $this->renderOutputUsingArray($res, $keyToIgnore, $key, $useEmpty);
                $output .= $this->renderFooter($key);
            } else {
                if (empty($res) && !$useEmpty) {
                    $res = 0;
                }

                if (isset($this->wsObject->urlFragments['schema'])) {
                    if ($this->wsObject->urlFragments['schema'] == 'blank' || $this->wsObject->urlFragments['schema'] == 'synopsis') {
                        $res = null;
                    } else {
                        throw new WebserviceException(
                            'Please select a schema of type \'synopsis\' to get the whole schema informations (which fields are required, which kind of content...) or \'blank\' to get an empty schema to fill before using POST request.',
                            array(100, 400)
                        );
                    }
                }

                $output .= $this->objOutput->objectRender->renderField(
                    array(
                        'sqlId' => $key,
                        'value' => $res
                    )
                );
            }
        }

        return $output;
    }

    public function createBooking($params)
    {
        $this->context->cart = new Cart();
        $this->processGuest($params['guest_detail']);
        $this->processLanguage($params);
        $this->processCurrency($params);
        //saving the cart after adding the guest, language and the currency in the cart
        $this->context->cart->save();
        $objHotelRoomType = new HotelRoomType();
        if ($this->addCartRooms($params)) {
            $totalAmount = isset($params['price_details']['total_paid']) ? $params['price_details']['total_paid'] : 0;
            $this->processCustomer($params['guest_detail']);
            $objPaymentModule = new WebserviceOrder();
            $paymentStatus = false;
            if (isset($params['payment_status'])) {
                $paymentStatus = $params['payment_status'];
            }

            switch ($paymentStatus) {
                case self::BOOKING_API_PAYMENT_STATUS_COMPLETED:
                    $orderStatus = $orderStatus = Configuration::get('PS_OS_PAYMENT_ACCEPTED');
                break;
                case self::BOOKING_API_PAYMENT_STATUS_PARTIAL:
                    $orderStatus = Configuration::get('PS_OS_PARTIAL_PAYMENT_ACCEPTED');
                break;
                case self::BOOKING_API_PAYMENT_STATUS_AWATING:
                    $orderStatus = Configuration::get('PS_OS_AWAITING_PAYMENT');
                break;
                default:
                    // @todo: needs discussion
                    $cartTotal = $this->context->cart->getOrderTotal(true, Cart::BOTH);
                    if ($totalAmount > 0 && $totalAmount < $cartTotal) {
                        $orderStatus = Configuration::get('PS_OS_PARTIAL_PAYMENT_ACCEPTED');
                    } else if ($totalAmount >= $cartTotal) {
                        $orderStatus = $orderStatus = Configuration::get('PS_OS_PAYMENT_ACCEPTED');
                    }  else {
                        $orderStatus = Configuration::get('PS_OS_AWAITING_PAYMENT');
                    }
            }

            if (isset($params['source']) && $params['source']) {
                $objPaymentModule->orderSource = $params['source'];
            }

            $extraVars = array();
            $message = null;
            if (isset($params['payment_detail']['transaction_id'])
                && $params['payment_detail']['transaction_id']
            ) {
                $extraVars['transaction_id'] = $params['payment_detail']['transaction_id'];
            }

            if (isset($params['remark'])) {
                $message = $params['remark'];
            }

            if (isset($params['payment_detail']['payment_method'])
                && $params['payment_detail']['payment_method']
            ) {
                $objPaymentModule->displayName = $params['payment_detail']['payment_method'];
            }

            // return $this->createOrder(
            //     $this->context->cart->id,
            //     $orderStatus,
            //     $totalAmount,
            //     $objPaymentModule->displayName,
            //     $message,
            //     $extraVars,
            //     $this->bookingCustomer->secure_key
            // );

            if ($objPaymentModule->validateOrder(
                $this->context->cart->id,
                $orderStatus,
                $totalAmount,
                $objPaymentModule->displayName,
                $message,
                $extraVars,
                null,
                false,
                $this->bookingCustomer->secure_key
            )) {
                $objOrder = new Order($objPaymentModule->currentOrder);
                if (isset($params['booking_date'])
                    && $params['booking_date']
                ) {
                    $objOrder->date_add = date('Y-m-d H:i:s', strtotime($params['booking_date']));
                }

                // last modified time can never be saved using save as it overrides it with the the current date.
                // if (isset($params['last_modified_time'])
                //     && $params['last_modified_time']
                // ) {
                //     $objOrder->date_upd = date('Y-m-d H:i:s', strtotime($params['last_modified_time']));
                // }

                // @todo:
                // update the tax and the total_amount_without_tax for rooms, services, facilities and order?
                // where to save payment details?

                // To update these fields ?

                // Order table fields
                // Total_paid
                // Total_paid_tax_incl
                // Total_paid_tax_excl
                // Total_paid_real
                // Advance_paid_amount

                // OrderDetails table fields
                // total_price_tax_incl
                // total_price_tax_excl
                // unit_price_tax_incl
                // unit_price_tax_excl
                // product_price

                $objOrder->save();
                $response = array();
                $response['status'] = true;
                $response['id_order'] = $objPaymentModule->currentOrder;
                $this->output = $response;
                $this->deleteFeaturePrices();

                return true;
            }
        }

        $this->wsObject->setError(400, 'Unable to create booking.', 200);

        return false;
    }

    public function processGuest($params)
    {
        $objCustomer = new Customer();
        $this->bookingCustomer = $objCustomer->getByEmail($params['email']);
        if (isset($this->bookingCustomer->id)
            && $this->bookingCustomer->id
        ) {
            $idGuest = Guest::getFromCustomer($objCustomer->id);
        } else {
            $idGuest = $this->createGuestForBooking();
        }

        $this->context->cart->id_guest = $idGuest;
    }

    public function processLanguage($params)
    {
        $idLang = Configuration::get('PS_LANG_DEFAULT');
        if (isset($params['language_iso'])
            && ($selectedLang = Language::getIdByIso($params['language_iso']))
        ) {
            $objLanguage = new Language($selectedLang);
            if ($objLanguage->active) {
                $idLang = $selectedLang;
            }
        }

        $this->context->cart->id_lang = $idLang;
    }

    public function processCurrency($params)
    {
        $idCurrency = Configuration::get('PS_CURRENCY_DEFAULT');
        if (isset($params['currency'])
            && ($selectedCurrency = Currency::getIdByIsoCode($params['currency']))
        ) {
            $objCurrency = new Currency($selectedCurrency);
            if ($objCurrency->active) {
                $idCurrency = $selectedCurrency;
            }
        }

        $this->context->cart->id_currency = $idCurrency;
    }

    public function addCartRooms($params)
    {
        $this->featurePrices = array();
        $cartData = $this->validateAndFormatCartData($params);
        $objHotelCartBookingData = new HotelCartBookingData();
        $objRoomType = new HotelRoomType();
        $objHotelCartBookingData = new HotelCartBookingData();
        $idHotel = $params['id_property'];
        if ($cartData['status']) {
            $status = true;
            foreach ($cartData['roomTypes'] as $roomType) {
                $dateFrom = date('Y-m-d', strtotime($roomType['checkin_date']));
                $dateTo = date('Y-m-d', strtotime($roomType['checkout_date']));
                $roomTypeInfo = $objRoomType->getRoomTypeInfoByIdProduct($roomType['id_room_type']);
                $occupancy = array(
                    array(
                        'adults' => $roomTypeInfo['adults'],
                        'children' => $roomTypeInfo['children'],
                        'child_ages' => array()
                    )
                );
                $productPriceTI = Product::getPriceStatic((int) $roomType['id_room_type'], true);
                $productPriceTE = Product::getPriceStatic((int) $roomType['id_room_type'], false);
                if ($productPriceTE) {
                    $taxRate = (($productPriceTI-$productPriceTE)/$productPriceTE)*100;
                } else {
                    $taxRate = 0;
                }

                $taxRateM = $taxRate/100;
                if (isset($roomType['rooms']) && count($roomType['rooms'])) {
                    foreach ($roomType['rooms'] as $room) {
                        $roomServiceProducts = array();
                        if (isset($room['selected_services'])
                            && is_array($room['selected_services'])
                            && count($room['selected_services'])
                        ) {
                            $roomServiceProducts = $room['selected_services'];
                        }

                        $roomDemands = json_encode(array());
                        if (isset($room['selected_demands'])
                            && is_array($room['selected_services'])
                            && count($room['selected_demands'])
                        ) {
                            $roomDemands = json_encode($room['selected_demands']);
                        }

                        if (isset($room['occupancy']) && count($room['occupancy'])) {
                            $occupancy = $room['occupancy'];
                        }

                        if ($idHtlCartBookingData = $objHotelCartBookingData->updateCartBooking(
                            $roomType['id_room_type'],
                            $occupancy,
                            'up',
                            $idHotel,
                            0,
                            $dateFrom,
                            $dateTo,
                            $roomDemands,
                            $roomServiceProducts,
                            $this->context->cart->id,
                            $this->context->cart->id_guest
                        )) {
                            $objCartBookingData = new HotelCartBookingData((int) $idHtlCartBookingData);
                            if (isset($room['total_price_with_tax'])) {
                                $room['total_price_with_tax'] = (float) $room['total_price_with_tax']/(1+$taxRateM);
                                // need the id Room of the latest added room type
                                $this->featurePrices[] = $this->createFeaturePrice(
                                    array(
                                        'id_product' => (int) $roomType['id_room_type'],
                                        'id_cart' => (int) $this->context->cart->id,
                                        'id_guest' => (int) $this->context->cart->id_guest,
                                        'date_from' => date('Y-m-d', strtotime($dateFrom)),
                                        'date_to' => date('Y-m-d', strtotime($dateTo)),
                                        'id_room' => $objCartBookingData->id_room,
                                        'price' => $room['total_price_with_tax']
                                    )
                                );
                            }
                        }
                    }
                } else {
                    $roomWiseOccupancy = $occupancy;
                    if (isset($roomType['total_rooms'])) {
                        while ($roomType['total_rooms'] > 1) {
                            $roomWiseOccupancy[] = reset($occupancy);
                            $roomType['total_rooms']--;
                        }
                    }

                    $objHotelCartBookingData->updateCartBooking(
                        $roomType['id_room_type'],
                        $roomWiseOccupancy,
                        'up',
                        $idHotel,
                        0,
                        $dateFrom,
                        $dateTo,
                        $roomDemands,
                        $roomServiceProducts,
                        $this->context->cart->id,
                        $this->context->cart->id_guest
                    );

                    if ($idRooms = $objHotelCartBookingData->getCustomerIdRoomsByIdCartIdProduct(
                        $this->context->cart->id,
                        $roomType['id_room_type'],
                        date('Y-m-d', strtotime($dateFrom)),
                        date('Y-m-d', strtotime($dateTo))
                    )) {
                        if (isset($roomType['total_price_with_tax'])) {
                            $roomType['total_price_with_tax'] = (float) $roomType['total_price_with_tax']/(1+$taxRateM);
                            $roomType['total_price_with_tax'] = $roomType['total_price_with_tax']/count($idRooms);
                            foreach ($idRooms as $idRoom) {
                                $this->featurePrices[] = $this->createFeaturePrice(
                                    array(
                                        'id_product' => (int) $roomType['id_room_type'],
                                        'id_cart' => (int) $this->context->cart->id,
                                        'id_guest' => (int) $this->context->cart->id_guest,
                                        'date_from' => date('Y-m-d', strtotime($dateFrom)),
                                        'date_to' => date('Y-m-d', strtotime($dateTo)),
                                        'id_room' => $idRoom['id_room'],
                                        'price' => $roomType['total_price_with_tax']
                                    )
                                );
                            }
                        }
                    }
                }
            }
        }

        return $cartData['status'];
    }

    public function validateAndFormatCartData($params)
    {
        $roomTypes = $this->formatRoomTypesFromRequest($params);
        $cartData = array();
        $objBookingDetail = new HotelBookingDetail();
        $objRoomType = new HotelRoomType();
        foreach ($roomTypes as $roomTypeKey => $roomType) {
            if ($rooms = $this->formatRoomFromRequest($roomType)) {
                foreach ($rooms as $roomKey => $room) {
                    $selectedDemands = $this->formatAndValidateDemandsFromRequest($room, $roomType['id_room_type']);
                    $selectedServices = $this->formatAndValidateServicesFromRequest($room, $roomType['id_room_type']);
                    $occupancy = $this->formatOccupancyFromRequest($room, $roomType['id_room_type']);
                    $rooms[$roomKey]['selected_demands'] = $selectedDemands;
                    $rooms[$roomKey]['occupancy'] = $occupancy;
                    $rooms[$roomKey]['selected_services'] = $selectedServices;
                }

                $roomTypes[$roomTypeKey]['rooms'] = $rooms;
            }
        }

        $canCreateBooking = $this->validateRoomsForBoooking($roomTypes);

        return array('status' => $canCreateBooking, 'roomTypes' => $roomTypes);
    }

    public function handlePutRequest($params)
    {
        $objOrder = new Order((int) $params['id_booking']);
        // $this->addOrderHistory($params);
        // $this->addCustomerMessage($params);
        $objHotelCartBookingData = new HotelCartBookingData();
        $objHotelBookingDetail = new HotelBookingDetail();
        $roomTypes = $this->formatRoomTypesFromRequest($params);
        $objRoomType = new HotelRoomType();
        $roomsToRemove = array();
        $roomsToAdd = array();
        $roomsToUpdate = array();
        if ($roomsInOrder = $objHotelBookingDetail->getOrderCurrentDataByOrderId($objOrder->id)) {
            foreach ($roomsInOrder as $orderRoomKey => $orderRoom) {
                $dateProductJoinKey = $orderRoom['id_product'].'_'.strtotime($orderRoom['date_from']).strtotime($orderRoom['date_to']);
                if (isset($roomTypes[$dateProductJoinKey])) {
                    if (isset($roomTypes[$dateProductJoinKey]['total_rooms'])) {
                        $roomsToUpdate[$dateProductJoinKey]['requested'][] = isset($roomTypes[$dateProductJoinKey]['rooms']) ? reset($roomTypes[$dateProductJoinKey]['rooms']) : array();
                        $roomsToUpdate[$dateProductJoinKey]['order'][] = $roomsInOrder[$orderRoomKey];
                        if ($roomTypes[$dateProductJoinKey]['total_rooms'] > 1) {
                            // Since we are traversing the rooms from order one by one.
                            $roomTypes[$dateProductJoinKey]['total_rooms']--;
                            if (isset($roomTypes[$dateProductJoinKey]['rooms'])) {
                                array_shift($roomTypes[$dateProductJoinKey]['rooms']);
                            }
                        } else {
                            unset($roomTypes[$dateProductJoinKey]);
                        }

                    }

                    unset($roomsInOrder[$orderRoomKey]);
                }
            }

            $roomsToRemove = $roomsInOrder;
        }

        $roomsToAdd = $roomTypes;
        $res = true;
        // Adding new rooms in the booking
        if (count($roomsToAdd)
            && $res
            && ($res = $this->validateRoomsForBoooking($roomsToAdd)) // updating the result
        ) {
            $this->createNewCartForOrder($objOrder->id);
            foreach ($roomsToAdd as $roomType) {
                $dateFrom = date('Y-m-d', strtotime($roomType['checkin_date']));
                $dateTo = date('Y-m-d', strtotime($roomType['checkout_date']));
                $roomTypeInfo = $objRoomType->getRoomTypeInfoByIdProduct($roomType['id_room_type']);
                $occupancy = array(
                    array(
                        'adults' => $roomTypeInfo['adults'],
                        'children' => $roomTypeInfo['children'],
                        'child_ages' => array()
                    )
                );
                $idHotel = $roomTypeInfo['id_hotel'];
                $productPriceTI = Product::getPriceStatic((int) $roomType['id_room_type'], true);
                $productPriceTE = Product::getPriceStatic((int) $roomType['id_room_type'], false);
                if ($productPriceTE) {
                    $taxRate = (($productPriceTI-$productPriceTE)/$productPriceTE)*100;
                } else {
                    $taxRate = 0;
                }

                $taxRateM =  $taxRate/100;
                if (isset($roomType['rooms']) && count($roomType['rooms'])) {
                    if ($rooms = $this->formatRoomFromRequest($roomType)) {
                        foreach ($rooms as $room) {
                            $roomServiceProducts = $this->formatAndValidateServicesFromRequest($room, $roomType['id_room_type']);
                            $roomDemands = json_encode($this->formatAndValidateDemandsFromRequest($room, $roomType['id_room_type']));
                            if (isset($room['adults'])) {
                                // Getting occupancy only if given else use base occupancy.
                                $occupancy = $this->formatOccupancyFromRequest($room, $roomType['id_room_type']);
                            }

                            if ($idHtlCartBookingData = $objHotelCartBookingData->updateCartBooking(
                                $roomType['id_room_type'],
                                $occupancy,
                                'up',
                                $idHotel,
                                0,
                                $dateFrom,
                                $dateTo,
                                $roomDemands,
                                $roomServiceProducts,
                                $this->context->cart->id,
                                $this->context->cart->id_guest
                            )) {
                                $objCartBookingData = new HotelCartBookingData((int) $idHtlCartBookingData);
                                if (isset($room['total_price_with_tax'])) {
                                    $room['total_price_with_tax'] = (float) $room['total_price_with_tax']/(1+$taxRateM);
                                    $this->featurePrices[] = $this->createFeaturePrice(
                                        array(
                                            'id_product' => (int) $roomType['id_room_type'],
                                            'id_cart' => (int) $this->context->cart->id,
                                            'id_guest' => (int) $this->context->cart->id_guest,
                                            'date_from' => date('Y-m-d', strtotime($dateFrom)),
                                            'date_to' => date('Y-m-d', strtotime($dateTo)),
                                            'id_room' => $objCartBookingData->id_room,
                                            'price' => $room['total_price_with_tax']
                                        )
                                    );
                                }
                            }
                        }
                    }
                } else {
                    $roomWiseOccupancy = $occupancy;
                    if (isset($roomType['total_rooms'])) {
                        while ($roomType['total_rooms'] > 1) {
                            $roomWiseOccupancy[] = reset($occupancy);
                            $roomType['total_rooms']--;
                        }
                    }

                    $objHotelCartBookingData->updateCartBooking(
                        $roomType['id_room_type'],
                        $roomWiseOccupancy,
                        'up',
                        $idHotel,
                        0,
                        $dateFrom,
                        $dateTo,
                        $roomDemands,
                        $roomServiceProducts,
                        $this->context->cart->id,
                        $this->context->cart->id_guest
                    );
                    // Creating the same feature pricing for all the rooms
                    if ($idRooms = $objHotelCartBookingData->getCustomerIdRoomsByIdCartIdProduct(
                        $this->context->cart->id,
                        $roomType['id_room_type'],
                        date('Y-m-d', strtotime($dateFrom)),
                        date('Y-m-d', strtotime($dateTo))
                    )) {
                        if (isset($roomType['total_price_with_tax'])) {
                            $roomType['total_price_with_tax'] = (float) $roomType['total_price_with_tax']/(1+$taxRateM);
                            $roomType['total_price_with_tax'] = $roomType['total_price_with_tax']; // no need to divide this as this is per product
                            foreach ($idRooms as $idRoom) {
                                $this->featurePrices[] = $this->createFeaturePrice(
                                    array(
                                        'id_product' => (int) $roomType['id_room_type'],
                                        'id_cart' => (int) $this->context->cart->id,
                                        'id_guest' => (int) $this->context->cart->id_guest,
                                        'date_from' => date('Y-m-d', strtotime($dateFrom)),
                                        'date_to' => date('Y-m-d', strtotime($dateTo)),
                                        'id_room' => $idRoom['id_room'],
                                        'price' => $roomType['total_price_with_tax']
                                    )
                                );
                            }
                        }
                    }
                }
            }

            $objCart = $this->context->cart;
            $objOrderDetail = new OrderDetail();
            $objOrderDetail->createList($objOrder, $objCart, $objOrder->getCurrentOrderState(), $objCart->getProducts(), 0, true, 0);

            // update totals amount of order
            // creating the new object to reload the data changes made while removing the rooms.
            $objOrder = new Order((int) $params['id_booking']);
            $objOrder->total_products += (float)$objCart->getOrderTotal(false, Cart::ONLY_PRODUCTS);
            $objOrder->total_products_wt += (float)$objCart->getOrderTotal(true, Cart::ONLY_PRODUCTS);
            $objOrder->total_paid += Tools::ps_round((float)($objCart->getOrderTotal(true, Cart::BOTH)), 2);
            $objOrder->total_paid_tax_excl += Tools::ps_round((float)($objCart->getOrderTotal(false, Cart::BOTH)), 2);
            $objOrder->total_paid_tax_incl += Tools::ps_round((float)($objCart->getOrderTotal(true, Cart::BOTH)), 2);
            $objOrder->total_discounts += (float)abs($objCart->getOrderTotal(true, Cart::ONLY_DISCOUNTS));
            $objOrder->total_discounts_tax_excl += (float)abs($objCart->getOrderTotal(false, Cart::ONLY_DISCOUNTS));
            $objOrder->total_discounts_tax_incl += (float)abs($objCart->getOrderTotal(true, Cart::ONLY_DISCOUNTS));

            // Save changes of order
            $res = $objOrder->update();
            $vatAddress = new Address((int) $objOrder->id_address_tax);
            $idLang = (int) $this->context->cart->id_lang;
            foreach ($roomsToAdd as $roomType) {
                $orderDetails = $objHotelBookingDetail->getPsOrderDetailsByProduct($roomType['id_room_type'], $objOrder->id);
                $IdOrderDetail = end($orderDetails)['id_order_detail']; // to get the max id_order_detail
                $objCartBookingData = new HotelCartBookingData();
                if ($cartBookingData = $objCartBookingData->getOnlyCartBookingData(
                    $this->context->cart->id,
                    $this->context->cart->id_guest,
                    $roomType['id_room_type']
                )) {
                    foreach ($cartBookingData as $cb_k => $cb_v) {
                        $objCartBookingData = new HotelCartBookingData($cb_v['id']);
                        $objCartBookingData->id_order = $objOrder->id;
                        $objCartBookingData->save();
                        $objBookingDetail = new HotelBookingDetail();
                        $objBookingDetail->id_product = $roomType['id_room_type'];
                        $objBookingDetail->id_order = $objOrder->id;
                        $objBookingDetail->id_order_detail = $IdOrderDetail;
                        $objBookingDetail->id_cart = $this->context->cart->id;
                        $objBookingDetail->id_room = $objCartBookingData->id_room;
                        $objBookingDetail->id_hotel = $objCartBookingData->id_hotel;
                        $objBookingDetail->id_customer = $objOrder->id_customer;
                        $objBookingDetail->booking_type = $objCartBookingData->booking_type;
                        $objBookingDetail->id_status = 1;
                        $objBookingDetail->comment = $objCartBookingData->comment;
                        $objBookingDetail->room_type_name = Product::getProductName($roomType['id_room_type'], null, $objOrder->id_lang);

                        $objBookingDetail->date_from = $objCartBookingData->date_from;
                        $objBookingDetail->date_to = $objCartBookingData->date_to;
                        $objBookingDetail->adults = $objCartBookingData->adults;
                        $objBookingDetail->children = $objCartBookingData->children;
                        $objBookingDetail->child_ages = $objCartBookingData->child_ages;

                        $total_price = HotelRoomTypeFeaturePricing::getRoomTypeTotalPrice(
                            $roomType['id_room_type'],
                            $objCartBookingData->date_from,
                            $objCartBookingData->date_to,
                            0,
                            Group::getCurrent()->id,
                            $this->context->cart->id,
                            $this->context->cart->id_guest,
                            $objCartBookingData->id_room,
                            0
                        );
                        $objBookingDetail->total_price_tax_excl = $total_price['total_price_tax_excl'];
                        $objBookingDetail->total_price_tax_incl = $total_price['total_price_tax_incl'];
                        $objBookingDetail->total_paid_amount = Tools::ps_round($total_price['total_price_tax_incl'], 5);

                        // Save hotel information/location/contact
                        if (Validate::isLoadedObject($objRoom = new HotelRoomInformation($objCartBookingData->id_room))) {
                            $objBookingDetail->room_num = $objRoom->room_num;
                        }

                        if (Validate::isLoadedObject($objHotelBranch = new HotelBranchInformation(
                            $objCartBookingData->id_hotel,
                            $idLang
                        ))) {
                            $addressInfo = $objHotelBranch->getAddress($objCartBookingData->id_hotel);
                            $objBookingDetail->hotel_name = $objHotelBranch->hotel_name;
                            $objBookingDetail->city = $addressInfo['city'];
                            $objBookingDetail->state = State::getNameById($addressInfo['id_state']);
                            $objBookingDetail->country = Country::getNameById($idLang, $addressInfo['id_country']);
                            $objBookingDetail->zipcode = $addressInfo['postcode'];;
                            $objBookingDetail->phone = $addressInfo['phone'];
                            $objBookingDetail->email = $objHotelBranch->email;
                            $objBookingDetail->check_in_time = $objHotelBranch->check_in;
                            $objBookingDetail->check_out_time = $objHotelBranch->check_out;
                        }

                        if ($roomTypeInfo = $objRoomType->getRoomTypeInfoByIdProduct($roomType['id_room_type'])) {
                            $objBookingDetail->adults = $objCartBookingData->adults;
                            $objBookingDetail->children = $objCartBookingData->children;
                            $objBookingDetail->child_ages = $objCartBookingData->child_ages;
                        }

                        if ($objBookingDetail->save()) {
                            $objRoomTypeServiceProduct = new RoomTypeServiceProduct();
                            $objRoomTypeServiceProductPrice = new RoomTypeServiceProductPrice();
                            $objRoomTypeServiceProductCartDetail = new RoomTypeServiceProductCartDetail();
                            if ($services = $objRoomTypeServiceProductCartDetail->getRoomServiceProducts($objCartBookingData->id)) {
                                foreach ($services as $service) {
                                    $insertedServiceProductIdOrderDetail = $objBookingDetail->getLastInsertedServiceIdOrderDetail($objOrder->id, $service['id_product']);
                                    $numDays = 1;
                                    if (Product::getProductPriceCalculation($service['id_product']) == Product::PRICE_CALCULATION_METHOD_PER_DAY) {
                                        $numDays = HotelHelper::getNumberOfDays($objBookingDetail->date_from, $objBookingDetail->date_to);
                                    }

                                    $totalPriceTaxExcl = $objRoomTypeServiceProductPrice->getServicePrice(
                                        (int) $service['id_product'],
                                        $roomTypeInfo['id'],
                                        $service['quantity'],
                                        $objBookingDetail->date_from,
                                        $objBookingDetail->date_to,
                                        false
                                    );
                                    $totalPriceTaxIncl = $objRoomTypeServiceProductPrice->getServicePrice(
                                        (int)$service['id_product'],
                                        $roomTypeInfo['id'],
                                        $service['quantity'],
                                        $objBookingDetail->date_from,
                                        $objBookingDetail->date_to,
                                        true
                                    );
                                    $unitPriceTaxExcl = $totalPriceTaxExcl / ($numDays * $service['quantity']);
                                    $unitPriceTaxIncl = $totalPriceTaxIncl / ($numDays * $service['quantity']);

                                    $objRoomTypeServiceProductOrderDetail = new RoomTypeServiceProductOrderDetail();
                                    $objRoomTypeServiceProductOrderDetail->id_product = $service['id_product'];
                                    $objRoomTypeServiceProductOrderDetail->id_order = $objBookingDetail->id_order;
                                    $objRoomTypeServiceProductOrderDetail->id_order_detail = $insertedServiceProductIdOrderDetail;
                                    $objRoomTypeServiceProductOrderDetail->id_cart = $this->context->cart->id;
                                    $objRoomTypeServiceProductOrderDetail->id_htl_booking_detail = $objBookingDetail->id;
                                    $objRoomTypeServiceProductOrderDetail->unit_price_tax_excl = $unitPriceTaxExcl;
                                    $objRoomTypeServiceProductOrderDetail->unit_price_tax_incl = $unitPriceTaxIncl;
                                    $objRoomTypeServiceProductOrderDetail->total_price_tax_excl = $totalPriceTaxExcl;
                                    $objRoomTypeServiceProductOrderDetail->total_price_tax_incl = $totalPriceTaxIncl;
                                    $objRoomTypeServiceProductOrderDetail->name = $service['name'];
                                    $objRoomTypeServiceProductOrderDetail->quantity = $service['quantity'];
                                    $objRoomTypeServiceProductOrderDetail->save();
                                }
                            }

                            if ($objCartBookingData->extra_demands
                                && ($extraDemands = json_decode($objCartBookingData->extra_demands, true))
                            ) {
                                $objRoomDemandPrice = new HotelRoomTypeDemandPrice();
                                foreach ($extraDemands as $demand) {
                                    $idGlobalDemand = $demand['id_global_demand'];
                                    $idOption = $demand['id_option'];
                                    $objBookingDemand = new HotelBookingDemands();
                                    $objBookingDemand->id_htl_booking = $objBookingDetail->id;
                                    $objGlobalDemand = new HotelRoomTypeGlobalDemand($idGlobalDemand, $idLang);
                                    if ($idOption) {
                                        $objOption = new HotelRoomTypeGlobalDemandAdvanceOption($idOption, $idLang);
                                        $objBookingDemand->name = $objOption->name;
                                    } else {
                                        $idOption = 0;
                                        $objBookingDemand->name = $objGlobalDemand->name;
                                    }
                                    $objBookingDemand->unit_price_tax_excl = HotelRoomTypeDemand::getPriceStatic(
                                        $roomType['id_room_type'],
                                        $idGlobalDemand,
                                        $idOption,
                                        0
                                    );
                                    $objBookingDemand->unit_price_tax_incl = HotelRoomTypeDemand::getPriceStatic(
                                        $roomType['id_room_type'],
                                        $idGlobalDemand,
                                        $idOption,
                                        1
                                    );
                                    $qty = 1;
                                    if ($objGlobalDemand->price_calc_method == HotelRoomTypeGlobalDemand::WK_PRICE_CALC_METHOD_EACH_DAY) {
                                        $numDays = $objBookingDetail->getNumberOfDays(
                                            $objBookingDetail->date_from,
                                            $objBookingDetail->date_to
                                        );
                                        if ($numDays > 1) {
                                            $qty *= $numDays;
                                        }
                                    }
                                    $objBookingDemand->total_price_tax_excl = $objBookingDemand->unit_price_tax_excl * $qty;
                                    $objBookingDemand->total_price_tax_incl = $objBookingDemand->unit_price_tax_incl * $qty;

                                    $objBookingDemand->price_calc_method = $objGlobalDemand->price_calc_method;
                                    $objBookingDemand->id_tax_rules_group = $objGlobalDemand->id_tax_rules_group;
                                    if ($objBookingDemand->save()
                                        && Validate::isLoadedObject($vatAddress)
                                    ) {
                                        $taxManager = TaxManagerFactory::getManager(
                                            $vatAddress,
                                            $objGlobalDemand->id_tax_rules_group
                                        );
                                        $taxCalc = $taxManager->getTaxCalculator();
                                        $objBookingDemand->tax_computation_method = (int)$taxCalc->computation_method;
                                        $objBookingDemand->tax_calculator = $taxCalc;
                                        // Now save tax details of the extra demand
                                        $objBookingDemand->setBookingDemandTaxDetails();
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $this->deleteFeaturePrices();
            HotelRoomTypeFeaturePricing::deleteByIdCart($this->context->cart->id);
        }

        if (count($roomsToRemove)) {
            $res = $this->removeBookingRoomLine($params, $roomsToRemove);
        }

        // Update the information for the services that were updated in the exesting rooms
        if (count($roomsToUpdate)) {
            $objRoomTypeServiceProductOrderDetail = new RoomTypeServiceProductOrderDetail();
            $objBookingDemand = new HotelBookingDemands();
            foreach ($roomsToUpdate as $roomsByDate) {
                if (isset($roomsByDate['requested'])
                    && count($roomsByDate['requested'])
                    && !empty($roomsByDate['requested'][0])
                ) {
                    foreach ($roomsByDate['requested'] as $roomsKey => $room) {
                        if (((float) $room['total_price_with_tax']) != ((float) $roomsByDate['order'][$roomsKey]['total_price_tax_incl'])) {
                            $this->updateRoomPrice($room, $roomsByDate['order'][$roomsKey]);
                        }

                        $idHotelBookingDetail = $roomsByDate['order'][$roomsKey]['id'];
                        $existingServices = $objRoomTypeServiceProductOrderDetail->getSelectedServicesForRoom($idHotelBookingDetail);
                        $requestedServices = $this->formatAndValidateServicesFromRequest($room, $roomsByDate['order'][$roomsKey]['id_product']);
                        $servicesToUpdate = array();
                        $servicesToAdd = array();
                        $servicesToRemove = array();
                        if (!empty($existingServices['additional_services'])) {
                            foreach ($existingServices['additional_services'] as $orderedService) {
                                if (isset($requestedServices[$orderedService['id_product']])) {
                                    // only updating the quantity for now, price updation might be added later
                                    if ($requestedServices[$orderedService['id_product']]['quantity'] != $orderedService['quantity']) {
                                        $orderedService['new_quantity'] = $requestedServices[$orderedService['id_product']]['quantity'];
                                        $orderedService['id_htl_booking_detail'] = $idHotelBookingDetail;
                                        $servicesToUpdate[] = $orderedService;
                                    }

                                    // Unsetting the existing service to filter the services that are not in order but in the request.
                                    unset($requestedServices[$orderedService['id_product']]);
                                } else {
                                    $servicesToRemove[] = $orderedService;
                                }
                            }
                        }

                        // adding the remaning services left after the filteration process
                        $servicesToAdd = $requestedServices;
                        $this->removeServicesFromRoom($servicesToRemove);
                        $this->addServicesInRoom($servicesToAdd, $idHotelBookingDetail);
                        $this->udpateServicesInRoom($servicesToUpdate);

                        $idOrder = $roomsByDate['order'][$roomsKey]['id_order'];
                        $idProduct = $roomsByDate['order'][$roomsKey]['id_product'];
                        $idRoom = $roomsByDate['order'][$roomsKey]['id_room'];
                        $dateFrom = $roomsByDate['order'][$roomsKey]['date_from'];
                        $dateTo = $roomsByDate['order'][$roomsKey]['date_to'];

                        // Since we don't store the id_global_demand in the order, we will remove the previous ones and add the new ones.
                        $demandsToAdd = array();
                        $demandsToRemove = array();
                        $requestedDemands = $this->formatAndValidateDemandsFromRequest($room, $roomsByDate['order'][$roomsKey]['id_product']);
                        $roomExtraDemand = $objBookingDemand->getRoomTypeBookingExtraDemands(
                            $idOrder,
                            $idProduct,
                            $idRoom,
                            $dateFrom,
                            $dateTo,
                            0
                        );
                        $this->removeDemandsFromRoom($roomExtraDemand);
                        $this->addDemandsInRoom($requestedDemands, $idHotelBookingDetail);
                    }
                }
            }
        }
        // add order payment later
        // $this->addOrderPayment($params);
        $response = array();
        $response['status'] = $res;
        $response['id_order'] = $objOrder->id;
        $this->output = $response;
    }

    public function validateRoomsForBoooking($rooms)
    {
        $canCreateBooking= true;
        $objBookingDetail = new HotelBookingDetail();
        $objRoomType = new HotelRoomType();
        $idHotel = false;
        foreach ($rooms as $room) {
            if (!Validate::isLoadedObject(new Product((int) $room['id_room_type']))
                || !Product::isBookingProduct((int) $room['id_room_type'])
            ) {
                $this->error_msg = Tools::displayError('Invalid room in the request');
                throw new WebserviceException(
                    $this->error_msg,
                    array(404, 400)
                );
            } else {
                if (!$idHotel) {
                    $roomTypeInfo = $objRoomType->getRoomTypeInfoByIdProduct($room['id_room_type']);
                    $idHotel = $roomTypeInfo['id_hotel'];
                }

                $dateFrom = date('Y-m-d', strtotime($room['checkin_date']));
                $dateTo = date('Y-m-d', strtotime($room['checkout_date']));
                $bookingParams = array(
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'hotel_id' => $idHotel,
                    'id_room_type' => $room['id_room_type'],
                    'only_search_data' => 1
                );
                if (strtotime($dateFrom) > strtotime($dateTo)) {
                    $canCreateBooking = false;
                } else {
                    if ($hotelRoomData = $objBookingDetail->dataForFrontSearch($bookingParams)) {
                        if ($hotelRoomData['stats']['num_avail'] < $room['total_rooms']) {
                            $canCreateBooking = false;
                        }
                    } else {
                        $canCreateBooking = false;
                    }
                }
            }
        }

        return $canCreateBooking;
    }

    public function removeBookingRoomLine($params, $roomsToRemove)
    {
        $res = true;
        $objOrder = new Order((int) $params['id_booking']);
        $objBookingDemand = new HotelBookingDemands();
        $objHotelBookingDetail = new HotelBookingDetail();
        $objRoomTypeServiceProductOrderDetail = new RoomTypeServiceProductOrderDetail();
        foreach ($roomsToRemove as $roomType) {
            if ($res) {
                $dateFrom = $roomType['date_from'];
                $dateTo = $roomType['date_to'];
                $quantity = (int) HotelHelper::getNumberOfDays($dateFrom, $dateTo);
                $objOrderDetail = new OrderDetail((int) $roomType['id_order_detail']);
                $idHotelBooking = $roomType['id'];
                $idHotel = $roomType['id_hotel'];
                $bookingPriceTaxIncl = $roomType['total_price_tax_incl'];
                $bookingPriceTaxExcl = $roomType['total_price_tax_excl'];
                $roomExtraDemandTI = $objBookingDemand->getRoomTypeBookingExtraDemands(
                    $objOrder->id,
                    $roomType['id_product'],
                    $roomType['id_room'],
                    $dateFrom,
                    $dateTo,
                    0,
                    1,
                    1
                );
                $roomExtraDemandTE = $objBookingDemand->getRoomTypeBookingExtraDemands(
                    $objOrder->id,
                    $roomType['id_product'],
                    $roomType['id_room'],
                    $dateFrom,
                    $dateTo,
                    0,
                    1,
                    0
                );
                $additionlServicesTI = $objRoomTypeServiceProductOrderDetail->getSelectedServicesForRoom(
                    $idHotelBooking,
                    1,
                    1
                );
                $additionlServicesTE = $objRoomTypeServiceProductOrderDetail->getSelectedServicesForRoom(
                    $idHotelBooking,
                    1,
                    0
                );
                $selectedAdditonalServices = $objRoomTypeServiceProductOrderDetail->getSelectedServicesForRoom(
                    $idHotelBooking
                );
                $diffProductsTaxIncl = $bookingPriceTaxIncl;
                $diffProductsTaxExcl = $bookingPriceTaxExcl;
                $objHotelBookingDetail = new HotelBookingDetail((int) $idHotelBooking);
                $roomQuantity = (int) HotelHelper::getNumberOfDays($dateFrom, $dateTo);
                if (isset($selectedAdditonalServices['additional_services'])
                    && count($selectedAdditonalServices['additional_services'])
                ) {
                    foreach ($selectedAdditonalServices['additional_services'] as $service) {
                        $serviceOrderDetail = new OrderDetail($service['id_order_detail']);
                        $numDays = 1;
                        if (Product::getProductPriceCalculation($service['id_product']) == Product::PRICE_CALCULATION_METHOD_PER_DAY) {
                            $numDays = HotelHelper::getNumberOfDays($objHotelBookingDetail->date_from, $objHotelBookingDetail->date_to);
                        }

                        $service['quantity'] *= $numDays;
                        if ($service['quantity'] >= $serviceOrderDetail->product_quantity) {
                            $serviceOrderDetail->delete();
                        } else {
                            // @todo:
                            // $orderDetail->total_price_tax_incl -= $service['total_price_tax_incl'];
                            // $orderDetail->total_price_tax_excl -= $service['total_price_tax_excl'];
                            // $serviceOldQuantity = $serviceOrderDetail->product_quantity;
                            // $serviceOrderDetail->product_quantity = $serviceOldQuantity - $service['quantity'];
                            // // update taxes
                            // $objOrderDetail->updateTaxAmount($order);
                            // $objOrderDetail->update();
                        }
                    }
                }

                // Update Order
                // values changes as values are calculated accoding to the quantity of the product by webkul
                $objOrder->total_paid = Tools::ps_round($objOrder->total_paid - ($diffProductsTaxIncl + $roomExtraDemandTI + $additionlServicesTI));
                $objOrder->total_paid_tax_incl = Tools::ps_round($objOrder->total_paid_tax_incl - ($diffProductsTaxIncl + $roomExtraDemandTI + $additionlServicesTI));
                $objOrder->total_paid_tax_excl = Tools::ps_round($objOrder->total_paid_tax_excl - ($diffProductsTaxExcl + $roomExtraDemandTE + $additionlServicesTE));
                $objOrder->total_products = Tools::ps_round($objOrder->total_products - ($diffProductsTaxExcl + $additionlServicesTE));
                $objOrder->total_products_wt = Tools::ps_round($objOrder->total_products_wt - ($diffProductsTaxIncl + $additionlServicesTI));

                if ($roomQuantity >= $objOrderDetail->product_quantity) {
                    $objOrderDetail->delete();
                } else {
                    $objOrderDetail->total_price_tax_incl -= $diffProductsTaxIncl;
                    $objOrderDetail->total_price_tax_excl -= $bookingPriceTaxExcl;
                    $oldRoomQuantity = $objOrderDetail->product_quantity;
                    $objOrderDetail->product_quantity = $oldRoomQuantity - $roomQuantity;
                    $objOrderDetail->reduction_percent = 0;
                    // update taxes
                    $objOrderDetail->updateTaxAmount($objOrder);
                    // Save order detail
                    $res &= $objOrderDetail->update();
                }

                $res &= $objOrder->update();
                // delete the demands of this booking
                $objBookingDemand->deleteBookingDemands($idHotelBooking);
                $objRoomTypeServiceProductOrderDetail->deleteRoomSevices($idHotelBooking);
                $objHotelCartBookingData = new HotelCartBookingData();
                $objHotelCartBookingData->deleteOrderedRoomFromCart(
                    $objOrder->id,
                    $idHotel,
                    $roomType['id_room'],
                    $dateFrom,
                    $dateTo
                );
                $objHotelBookingDetail = new HotelBookingDetail();
                $objHotelBookingDetail->deleteOrderedRoomFromOrder(
                    $objOrder->id,
                    $idHotel,
                    $roomType['id_room'],
                    $dateFrom,
                    $dateTo
                );
            }
        }

        return $res;
    }

    public function formatRoomTypesFromRequest($params)
    {
        $roomTypes = array();
        if (isset($params['room_types']['room_type'][0])) {
            $roomTypes = $params['room_types']['room_type'];
        } else if (isset($params['room_types'])
            && !isset($params['room_types'][0]) // to Handle JSON requests
        ) {
            $roomTypes[] = $params['room_types']['room_type'];
        } else if (isset($params['room_types']) && isset($params['room_types'][0])) {
            // for the Json
            $roomTypes = $params['room_types'];
        }

        if (count($roomTypes)) {
            $formattedRoomTypes = array();
            foreach ($roomTypes as $roomTypeKey => $roomType) {
                $dateProductJoinKey = $roomType['id_room_type'].'_'.strtotime($roomType['checkin_date']).strtotime($roomType['checkout_date']);
                if (!isset($formattedRoomTypes[$dateProductJoinKey])) {
                    $formattedRoomTypes[$dateProductJoinKey] = $roomType;
                } else {
                    $formattedRoomTypes[$dateProductJoinKey]['total_rooms'] += $roomType['total_rooms'];
                }

                if (isset($roomType['rooms'])) {
                    $formattedRoomTypes[$dateProductJoinKey]['rooms'] = $this->formatRoomFromRequest($roomType);
                }
            }

            $roomTypes = $formattedRoomTypes;
        }


        return $roomTypes;
    }

    public function formatRoomFromRequest($roomType)
    {
        $rooms = array();
        if (isset($roomType['rooms']['room'][0])) {
            $rooms = $roomType['rooms']['room'];
        } else if (isset($roomType['rooms'])
            && !isset($roomType['rooms'][0]) // to Handle JSON requests
        ) {
            $rooms[] = $roomType['rooms']['room'];
        } else if (isset($roomType['rooms']) && isset($roomType['rooms'][0])) {
            // for the Json
            $rooms = $roomType['rooms'];
        }

        return $rooms;
    }

    public function formatOccupancyFromRequest($room, $idRoomType)
    {
        $child_ages = array();
        if (isset($room['child_ages']['child_age'][0])
            && is_array($room['child_ages']['child_age'])
        ) {
            $child_ages = $room['child_ages']['child_age'];
        } else if (isset($room['child_ages'])
            && !isset($room['child_ages'][0]) // to Handle JSON requests
        ) {
            $child_ages[] = $room['child_ages']['child_age'];
        } else if (isset($room['child_ages']) && isset($room['child_ages'][0])) {
            // for the Json
            $child_ages = $room['child_ages'];
        }

        $objRoomType = new HotelRoomType();
        // using to set base occupancy for the room
        $roomTypeInfo = $objRoomType->getRoomTypeInfoByIdProduct($idRoomType);

        return array(
            array(
                'adults' => isset($room['adult']) ? $room['adult'] :$roomTypeInfo['adults'],
                'children' => isset($room['child']) ? $room['child'] :$roomTypeInfo['children'],
                'child_ages' => $child_ages
            )
        );
    }

    public function formatAndValidateServicesFromRequest($room, $roomTypeId)
    {
        $selectedServices = array();
        if (isset($room['services']['service'][0])) {
            $selectedServices = $room['services']['service'];
        } else if (isset($room['services'])
            && !isset($room['services'][0]) // to Handle JSON requests
        ) {
            $selectedServices[] = $room['services']['service'];
        } else if (isset($room['services']) && isset($room['services'][0])) {
            // for the Json
            $selectedServices = $room['services'];
        }

        $objHotelRoomTypeServiceProduct = new RoomTypeServiceProduct();
        if ($selectedServices && count($selectedServices)) {
            foreach ($selectedServices as $key => $serviceProduct) {
                $selectedServices[$key] = array();
                $selectedServices[$key]['id_product'] = $serviceProduct['id_service'];
                if (Validate::isLoadedObject($objServiceProduct = new Product($serviceProduct['id_service']))) {
                    if (!$objHotelRoomTypeServiceProduct->isRoomTypeLinkedWithProduct($roomTypeId, $serviceProduct['id_service'])) {
                        unset($selectedServices[$key]);
                    } else if (isset($serviceProduct['quantity'])) {
                        $selectedServices[$key]['allow_multiple_quantity'] = $objServiceProduct->allow_multiple_quantity;
                        if (!$objServiceProduct->allow_multiple_quantity && $serviceProduct['quantity'] >= 1) {
                            $selectedServices[$key]['quantity'] = 1;
                        } else if ($objServiceProduct->max_quantity && $objServiceProduct->max_quantity < $serviceProduct['quantity'] ) {
                            $selectedServices[$key]['quantity'] = $objServiceProduct->max_quantity;
                        } else if ($objServiceProduct->allow_multiple_quantity && $serviceProduct['quantity']) {
                            $selectedServices[$key]['quantity'] = $serviceProduct['quantity'];
                        }
                    } else {
                        $selectedServices[$key]['quantity'] = 1;
                    }
                } else {
                    unset($selectedServices[$key]);
                }
            }
        }

        // need to format service for the put request
        $formattedServices = array();
        if (count($selectedServices)) {
            foreach ($selectedServices as $service) {
                $key = $service['id_product'];
                $formattedServices[$key] = $service;
            }

            $selectedServices = $formattedServices;
        }

        return $selectedServices;
    }

    public function formatAndValidateDemandsFromRequest($room, $roomTypeId)
    {
        $selectedDemands = array();
        if (isset($room['facilities']['facility'][0])) {
            $selectedDemands = $room['facilities']['facility'];
        } else if (isset($room['facilities'])
            && !isset($room['facilities'][0]) // to Handle JSON requests
        ) {
            $selectedDemands[] = $room['facilities']['facility'];
        } else if (isset($room['facilities']) && isset($room['facilities'][0])) {
            // for the Json
            $selectedDemands = $room['facilities'];
        }

        $objHotelDemandOptions = new HotelRoomTypeGlobalDemandAdvanceOption();
        if ($selectedDemands && count($selectedDemands)) {
            $objHotelRoomTypeDemand = new HotelRoomTypeDemand();
            if ($roomTypeDemands = $objHotelRoomTypeDemand->getRoomTypeDemands($roomTypeId)) {
                foreach ($selectedDemands as $demandKey => $reqDemand) {
                    unset($selectedDemands[$demandKey]); // remove older data
                    if (isset($roomTypeDemands[$reqDemand['id_facility']])) {
                        $selectedDemands[$demandKey]['id_global_demand'] = $reqDemand['id_facility'];
                        $selectedDemands[$demandKey]['id_option'] = 0;
                        if (isset($roomTypeDemands[$reqDemand['id_facility']]['adv_option'])) {
                            if (isset($reqDemand['id_option'])
                                && $reqDemand['id_option'] > 0
                                && isset($roomTypeDemands[$reqDemand['id_facility']]['adv_option'][$reqDemand['id_option']])
                            ) {
                                $selectedDemands[$demandKey]['id_option'] = $reqDemand['id_option'];
                            } else {
                                // not using array_first_key since it is available after 7.0 php, also foreach is fastest way to get the first key for an array
                                foreach ($roomTypeDemands[$reqDemand['id_facility']]['adv_option'] as $optionKey => $option) {
                                    $selectedDemands[$demandKey]['id_option'] = $optionKey;
                                    break;
                                }
                            }
                        }
                    }
                }
            } else {
                $selectedDemands = array();
            }
        }

        return $selectedDemands;
    }

    public function addOrderHistory($params)
    {
        $objOrder = new Order((int) $params['id_booking']);
        $orderStatus = false;
        if ($params['booking_status'] == self::BOOKING_API_BOOKING_STATUS_CANCELLED) {
            $orderStatus = new OrderState(Configuration::get('PS_OS_CANCELED'));
        } else if ($params['booking_status'] == self::BOOKING_API_BOOKING_STATUS_REFUNDED) {
            $orderStatus = new OrderState(Configuration::get('PS_OS_REFUND'));
        } else if ($params['booking_status'] == self::BOOKING_API_BOOKING_STATUS_COMPLETED) {
            $orderStatus = new OrderState(Configuration::get('PS_OS_PAYMENT_ACCEPTED'));
        } else if ($params['booking_status'] == self::BOOKING_API_BOOKING_STATUS_NEW) {
            // $this->addOrderPayment($params);
        }

        if ($orderStatus) {
            $currentOrderStatus = $objOrder->getCurrentOrderState();
            if ($currentOrderStatus->id != $orderStatus->id) {
                $objOrderHistory = new OrderHistory();
                $objOrderHistory->id_order = $objOrder->id;
                // @todo: needs discussion since there is no id_employee in the API
                // $objOrderHistory->id_employee = (int) $this->context->employee->id;

                $useExistingsPayment = false;
                if (!$objOrder->hasInvoice()) {
                    $useExistingsPayment = true;
                }

                $objOrderHistory->changeIdOrderState((int)$orderStatus->id, $objOrder, $useExistingsPayment);

                $carrier = new Carrier($objOrder->id_carrier, $objOrder->id_lang);
                $templateVars = array();

                // Save all changes
                if ($objOrderHistory->add(true, $templateVars)) {
                    // synchronizes quantities if needed..
                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                        foreach ($objOrder->getProducts() as $product) {
                            if (StockAvailable::dependsOnStock($product['product_id'])) {
                                StockAvailable::synchronize($product['product_id'], (int)$product['id_shop']);
                            }
                        }
                    }
                }
            }
        }
    }

    public function addCustomerMessage($params)
    {
        if (isset($params['remark']) && !empty(trim($params['remark']))) {
            $objOrder = new Order((int) $params['id_booking']);
            $objMessage = new Message();
            $message = strip_tags($params['remark'], '<br>');
            $idCart = Cart::getCartIdByOrderId($objOrder->id);
            if (Validate::isCleanHtml($message)) {
                $objMessage->message = $message;
                $objMessage->id_cart = (int) $idCart;
                $objMessage->id_customer = (int) ($objOrder->id_customer);
                $objMessage->id_order = (int) $objOrder->id;
                $objMessage->private = 1;
                $objMessage->add();
            }
        }
    }

    public function addOrderPayment($params)
    {
        if (isset($params['payment_detail']) && $params['payment_detail']
            && isset($params['price_details']['total_paid']) && $params['price_details']['total_paid']
        ) {
            $amount = $params['price_details']['total_paid'];
            $objOrder = new Order($params['id_booking']);
            $paymentMethod = null;
            if (isset($params['payment_details']['payment_method']) && $params['payment_details']['payment_method']) {
                $paymentMethod = $params['payment_details']['payment_method'];
            }

            $transactionId = null;
            if (isset($params['payment_details']['transaction_id']) && $params['payment_details']['transaction_id']) {
                $transactionId = $params['payment_details']['transaction_id'];
            }

            $paymentType = null;
            if (isset($params['payment_details']['payment_type']) && $params['payment_details']['payment_type']) {
                $paymentType = $params['payment_details']['payment_type'];
            }

            $paymentType = OrderPayment::PAYMENT_TYPE_ONLINE;
            $idCurrency = $objOrder->id_currency;
            $paymentCurrency = null;
            if (isset($params['currency'])
                && ($selectedCurrency = Currency::getIdByIsoCode($params['currency']))
            ) {
                $objCurrency = new Currency($selectedCurrency);
                if ($objCurrency->active) {
                    $idCurrency = $selectedCurrency;
                }
            }

            if ($idCurrency != $objOrder->id_currency) {
                $newCurrency = new Currency($idCurrency);
            }

            $orderInvoice = null;
            if ($invoice = $objOrder->hasInvoice()) {
                $orderInvoice = new OrderInvoice((int) $invoice);
            }

            $objOrder->addOrderPayment(
                $amount,
                $paymentMethod,
                $transactionId,
                $newCurrency,
                null,
                $orderInvoice,
                $paymentType
            );

        }
    }

    public function createNewCartForOrder($idOrder)
    {
        $objOrder = new Order((int) $idOrder);
        $objCart = new Cart();
        $objCart->id_shop_group = $objOrder->id_shop_group;
        $objCart->id_shop = $objOrder->id_shop;
        $objCart->id_customer = $objOrder->id_customer;
        $objCart->id_carrier = $objOrder->id_carrier;
        $objCart->id_address_delivery = $objOrder->id_address_delivery;
        $objCart->id_address_invoice = $objOrder->id_address_invoice;
        $objCart->id_currency = $objOrder->id_currency;
        $objCart->id_lang = $objOrder->id_lang;
        $objCart->secure_key = $objOrder->secure_key;
        $objCart->id_guest = Guest::getFromCustomer((int) $objOrder->id_customer);
        // Save new cart
        $objCart->add();

        // Save context (in order to apply cart rule)
        $this->context->cart = $objCart;
        $this->context->customer = new Customer((int) $objOrder->id_customer);
    }

    public function validatePutRequestParams($params)
    {
        $this->error_msg = '';
        if (!isset($params['id_booking'])
            || !$params['id_booking']
            || !Validate::isLoadedObject(new Order($params['id_booking']))
        ) {
            $this->error_msg = Tools::displayError('Booking not found!!');
        } else if (!isset($params['booking_status'])
            || !$params['booking_status']
        ) {
            $this->error_msg = Tools::displayError('Invalid booking status');
        } else if (!isset($params['payment_status'])
            || !$params['payment_status']
        ) {
            $this->error_msg = Tools::displayError('Invalid payment status');
        }

        if (!$this->error_msg && $this->error_msg == '') {
            return true;
        }

        return false;
    }

    public function createFeaturePrice($params)
    {
        $feature_price_name = array();
        foreach (Language::getIDs(true) as $idLang) {
            $feature_price_name[$idLang] = 'Api-Booking-Price';
        }

        $hrt_feature_price = new HotelRoomTypeFeaturePricing();
        $hrt_feature_price->id_product = (int) $params['id_product'];
        $hrt_feature_price->id_cart = (int) $params['id_cart'];
        $hrt_feature_price->id_guest = (int) $params['id_guest'];
        $hrt_feature_price->id_room = (int) $params['id_room'];
        $hrt_feature_price->feature_price_name = $feature_price_name;
        $hrt_feature_price->date_selection_type = HotelRoomTypeFeaturePricing::DATE_SELECTION_TYPE_RANGE;
        $hrt_feature_price->date_from = date('Y-m-d', strtotime($params['date_from']));
        $hrt_feature_price->date_to = date('Y-m-d', strtotime($params['date_to']));
        $hrt_feature_price->is_special_days_exists = 0;
        $hrt_feature_price->special_days = json_encode(false);
        $hrt_feature_price->impact_way = HotelRoomTypeFeaturePricing::IMPACT_WAY_FIX_PRICE;
        $hrt_feature_price->impact_type = HotelRoomTypeFeaturePricing::IMPACT_TYPE_FIXED_PRICE;
        $hrt_feature_price->impact_value = $params['price'];
        $hrt_feature_price->active = 1;
        $hrt_feature_price->groupBox = array_column(Group::getGroups(Configuration::get('PS_LANG_DEFAULT')), 'id_group');

        if ($hrt_feature_price->add()) {
            return $hrt_feature_price->id;
        }

        return false;
    }

    public function deleteFeaturePrices()
    {
        if (isset($this->featurePrices) && is_array($this->featurePrices) && count($this->featurePrices)) {
            foreach ($this->featurePrices as $idFeaturePrice) {
                // To filter false ids
                if ($idFeaturePrice) {
                    $objFeaturePrice = new HotelRoomTypeFeaturePricing((int) $idFeaturePrice);
                    $objFeaturePrice->delete();
                }
            }
        }
    }

    public function processCustomer($params)
    {
        $this->context->cookie->id_guest = $this->context->cart->id_guest;
        if (!isset($this->bookingCustomer->id)) {
            $objCustomer = new Customer();
            $objCustomer->firstname = $params['firstname'];
            $objCustomer->lastname = $params['lastname'];
            $objCustomer->email = $params['email'];
            $objCustomer->passwd = md5(time()._COOKIE_KEY_);
            $objCustomer->cleanGroups();
            $objCustomer->add();
            $this->bookingCustomer = $objCustomer;
        } else {
            // update name
            $this->bookingCustomer->firstname = $params['firstname'];
            $this->bookingCustomer->lastname = $params['lastname'];
            $this->bookingCustomer->email = $params['email'];
            $this->bookingCustomer->save();
        }

        if (isset($params['country_code'])
            && $params['country_code']
        ) {
            $params['id_country'] = Country::getByIso($params['country_code']);
            if (isset($params['state_code'])
                && $params['state_code']
            ) {
                $params['id_state'] = State::getByIso($params['state_code']);
            }

            //incase the address for the country is invalid we will use the hotel address
            $objCountry = new Country($params['id_country']);
            if ($objCountry->contains_states
                && !isset($params['id_state'])
                || !$objCountry->active
            ) {
                unset($params['id_country']);
                if (isset($params['address'])) {
                    unset($params['address']);
                }

                if (isset($params['city'])) {
                    unset($params['city']);
                }

                if (isset($params['zip'])) {
                    unset($params['zip']);
                }
            }
        }

        // to remove the older non ordered cart for this customer.
        $this->context->cookie->id_cart = $this->context->cart->id;
        $this->context->updateCustomer($this->bookingCustomer, 1);
        $active = true;
        $cache_id = 'Address::getFirstCustomerAddressId_'.(int) $this->bookingCustomer->id.'-'.(bool)$active;
        Cache::clean($cache_id);
        if (!Address::getFirstCustomerAddressId($this->bookingCustomer->id)) {
            $objHtlCart = new HotelCartBookingData();
            if ($htlCartInfo = $objHtlCart->getCartCurrentDataByCartId($this->context->cart->id)) {
                if (isset($htlCartInfo[0]['id_hotel']) && ($idHotel = $htlCartInfo[0]['id_hotel'])) {
                    if ($address_info = HotelBranchInformation::getAddress($idHotel)) {
                        $objAddress = new Address();
                        $objAddress->id_customer = $this->bookingCustomer->id;
                        $objAddress->lastname = $params['firstname'];
                        $objAddress->firstname = $params['lastname'];
                        $objAddress->phone = $params['phone'];
                        $objAddress->alias = 'My address';
                        $objAddress->auto_generated = true;
                        $objAddress->address1 = isset($params['address']) ? $params['address'] : $address_info['address1'];
                        $objAddress->city = isset($params['city']) ? $params['city'] : $address_info['city'];
                        $objAddress->postcode = isset($params['zip']) ? $params['zip'] :  $address_info['postcode'];
                        $objAddress->id_country = isset($params['id_country']) ? $params['id_country'] : $address_info['id_country'];
                        $objAddress->id_state = isset($params['id_state']) ? $params['id_state'] : $address_info['id_state'];
                        $objAddress->save();
                    }
                }
            }
        }
    }

    public function updateRoomPrice($room, $bookingData)
    {
        $idHotelBooking = $bookingData['id'];
        if (Validate::isLoadedObject($objHotelBookingDetail = new HotelBookingDetail((int) $idHotelBooking))) {
            $objOrder = new Order((int) $objHotelBookingDetail->id_order);
                $objCart = new Cart($objOrder->id_cart);
            //removing the old price
            $objOrder->total_paid -= $objHotelBookingDetail->total_price_tax_incl;
            $objOrder->total_paid_tax_incl -= $objHotelBookingDetail->total_price_tax_incl;
            $objOrder->total_paid_tax_excl -= $objHotelBookingDetail->total_price_tax_excl;
            $objOrder->total_products -= $objHotelBookingDetail->total_price_tax_excl;
            $objOrder->total_products_wt -= $objHotelBookingDetail->total_price_tax_incl;

            $productPriceTI = Product::getPriceStatic((int) $objHotelBookingDetail->id_product, true);
            $productPriceTE = Product::getPriceStatic((int) $objHotelBookingDetail->id_product, false);
            if ($productPriceTE) {
                $taxRate = (($productPriceTI-$productPriceTE)/$productPriceTE)*100;
            } else {
                $taxRate = 0;
            }

            $taxRateM =  $taxRate/100;
            if (isset($room['total_price_with_tax'])) {
                $room['total_price_with_tax'] = (float) $room['total_price_with_tax']/ (1+$taxRateM);
                $this->featurePrices[] = $this->createFeaturePrice(
                    array(
                        'id_product' => (int) $objHotelBookingDetail->id_product,
                        'id_cart' => (int) $objCart->id,
                        'id_guest' => (int) $objCart->id_guest,
                        'date_from' => date('Y-m-d', strtotime($objHotelBookingDetail->date_from)),
                        'date_to' => date('Y-m-d', strtotime($objHotelBookingDetail->date_to)),
                        'id_room' => $objHotelBookingDetail->id_room,
                        'price' => $room['total_price_with_tax']
                    )
                );
            }

            $roomTotalPrice = HotelRoomTypeFeaturePricing::getRoomTypeTotalPrice(
                $objHotelBookingDetail->id_product,
                $objHotelBookingDetail->date_from,
                $objHotelBookingDetail->date_to,
                0,
                Group::getCurrent()->id,
                $objCart->id,
                $objCart->id_guest,
                $objHotelBookingDetail->id_room,
                0
            );

            $objHotelBookingDetail->total_price_tax_incl = $roomTotalPrice['total_price_tax_incl'];
            $objHotelBookingDetail->total_price_tax_excl = $roomTotalPrice['total_price_tax_excl'];
            $objHotelBookingDetail->total_paid_amount = $roomTotalPrice['total_price_tax_excl'];
            $objHotelBookingDetail->save();

            // Updating the price
            $objOrder->total_paid += $objHotelBookingDetail->total_price_tax_incl;
            $objOrder->total_paid_tax_incl += $objHotelBookingDetail->total_price_tax_incl;
            $objOrder->total_paid_tax_excl += $objHotelBookingDetail->total_price_tax_excl;
            $objOrder->total_products += $objHotelBookingDetail->total_price_tax_excl;
            $objOrder->total_products_wt += $objHotelBookingDetail->total_price_tax_incl;
            $objOrder->update();

            HotelRoomTypeFeaturePricing::deleteByIdCart($objCart->id);
            $this->deleteFeaturePrices();
        }
    }

    public function removeServicesFromRoom($services)
    {
        if (count($services)) {
            foreach ($services as $service) {
                $idRoomTypeServiceProductOrderDetail = $service['id_room_type_service_product_order_detail'];
                if (Validate::isLoadedObject($objRoomTypeServiceProductOrderDetail = new RoomTypeServiceProductOrderDetail((int) $idRoomTypeServiceProductOrderDetail))) {
                    $objOrderDetail = new OrderDetail((int) $objRoomTypeServiceProductOrderDetail->id_order_detail);
                    $priceTaxExcl = $objRoomTypeServiceProductOrderDetail->total_price_tax_excl;
                    $priceTaxIncl = $objRoomTypeServiceProductOrderDetail->total_price_tax_incl;
                    $quantity = $objRoomTypeServiceProductOrderDetail->quantity;
                    $objHotelBookingDetail = new HotelBookingDetail($objRoomTypeServiceProductOrderDetail->id_htl_booking_detail);

                    if ($objRoomTypeServiceProductOrderDetail->delete()) {
                        $objOrder = new Order($objRoomTypeServiceProductOrderDetail->id_order);
                        if ($quantity >= $objOrderDetail->product_quantity) {
                            $objOrderDetail->delete();
                        } else {
                            $objOrderDetail->product_quantity -= $quantity;

                            $objOrderDetail->total_price_tax_excl -= $priceTaxExcl;
                            $objOrderDetail->total_price_tax_incl -= $priceTaxIncl;

                            $objOrderDetail->updateTaxAmount($objOrder);

                            $objOrderDetail->update();
                        }

                        $objOrder->total_paid_tax_excl -= $priceTaxExcl;
                        $objOrder->total_paid_tax_incl -= $priceTaxIncl;
                        $objOrder->total_paid -= $priceTaxIncl;

                        $objOrder->update();
                    }
                }
            }
        }
    }

    // Does not contain the validations for the services since we are validating the products during the request formatting
    public function addServicesInRoom($services, $idHotelBookingDetail)
    {
        if (count($services)) {
            if ($services) {
                $objHotelBookingDetail = new HotelBookingDetail((int) $idHotelBookingDetail);
                $objOrder = new Order($objHotelBookingDetail->id_order);
                // set context currency So that we can get prices in the order currency
                $this->context->currency = new Currency($objOrder->id_currency);
                $objHotelRoomType = new HotelRoomType();
                $objHotelCartBookingData = new HotelCartBookingData();
                $objRoomTypeServiceProduct = new RoomTypeServiceProduct();
                $objRoomTypeServiceProductPrice = new RoomTypeServiceProductPrice();
                $objRoomTypeServiceProductCartDetail = new RoomTypeServiceProductCartDetail();
                $roomHtlCartInfo = $objHotelCartBookingData->getRoomRowByIdProductIdRoomInDateRange(
                    $objHotelBookingDetail->id_cart,
                    $objHotelBookingDetail->id_product,
                    $objHotelBookingDetail->date_from,
                    $objHotelBookingDetail->date_to,
                    $objHotelBookingDetail->id_room
                );

                $this->createNewCartForOrder($objOrder->id);
                $objCart = $this->context->cart;
                foreach ($services as $service) {
                    $objRoomTypeServiceProductCartDetail->addServiceProductInCart(
                        $service['id_product'],
                        $service['quantity'],
                        $objCart->id,
                        $roomHtlCartInfo['id']
                    );
                }

                $totalPriceChangeTaxExcl = 0;
                $totalPriceChangeTaxIncl = 0;
                $unitPriceTaxIncl = 0;
                $unitPriceTaxExcl = 0;
                $productList = $objCart->getProducts();
                $objOrderDetail = new OrderDetail();
                $objOrderDetail->createList($objOrder, $objCart, $objOrder->getCurrentOrderState(), $productList, 0, true);
                foreach ($productList as &$product) {
                    // This is used to get the actual quanity of the service as it is calculated incorrectly if the service is per night
                    if ($idRoomTypeServiceProductCartDetail = $objRoomTypeServiceProductCartDetail->alreadyExists(
                        $product['id_product'],
                        $objCart->id,
                        $roomHtlCartInfo['id'])
                    ) {
                        $objRoomTypeServiceProductCartDetail = new RoomTypeServiceProductCartDetail((int) $idRoomTypeServiceProductCartDetail);
                        $totalPriceChangeTaxExcl = $totalPriceTaxExcl = $objRoomTypeServiceProductPrice->getServicePrice(
                            (int) $product['id_product'],
                            0,
                            $objRoomTypeServiceProductCartDetail->quantity,
                            $objHotelBookingDetail->date_from,
                            $objHotelBookingDetail->date_to,
                            false,
                            $objCart->id
                        );
                        $totalPriceChangeTaxIncl = $totalPriceTaxIncl = $objRoomTypeServiceProductPrice->getServicePrice(
                            (int)$product['id_product'],
                            0,
                            $objRoomTypeServiceProductCartDetail->quantity,
                            $objHotelBookingDetail->date_from,
                            $objHotelBookingDetail->date_to,
                            true,
                            $objCart->id
                        );

                        $numDays = 1;
                        if (Product::getProductPriceCalculation($product['id_product']) == Product::PRICE_CALCULATION_METHOD_PER_DAY) {
                            $numDays = HotelHelper::getNumberOfDays($objHotelBookingDetail->date_from, $objHotelBookingDetail->date_to);
                        }

                        $product['cart_quantity'] = $objRoomTypeServiceProductCartDetail->quantity * $numDays;
                        $unitPriceTaxExcl = $objRoomTypeServiceProductPrice->getServicePrice(
                            (int) $product['id_product'],
                            0,
                            1,
                            $objHotelBookingDetail->date_from,
                            $objHotelBookingDetail->date_to,
                            false,
                            $objCart->id
                        )/ $numDays;
                        $unitPriceTaxIncl = $objRoomTypeServiceProductPrice->getServicePrice(
                            (int) $product['id_product'],
                            0,
                            1,
                            $objHotelBookingDetail->date_from,
                            $objHotelBookingDetail->date_to,
                            true,
                            $objCart->id
                        )/ $numDays;

                        $objRoomTypeServiceProductOrderDetail = new RoomTypeServiceProductOrderDetail();
                        $objRoomTypeServiceProductOrderDetail->id_product = $product['id_product'];
                        $objRoomTypeServiceProductOrderDetail->id_order = $objHotelBookingDetail->id_order;
                        $objRoomTypeServiceProductOrderDetail->id_order_detail = $objOrderDetail->id;
                        $objRoomTypeServiceProductOrderDetail->id_cart = $objCart->id;
                        $objRoomTypeServiceProductOrderDetail->id_htl_booking_detail = $objHotelBookingDetail->id;
                        $objRoomTypeServiceProductOrderDetail->unit_price_tax_excl = $unitPriceTaxExcl;
                        $objRoomTypeServiceProductOrderDetail->unit_price_tax_incl = $unitPriceTaxIncl;
                        $objRoomTypeServiceProductOrderDetail->total_price_tax_excl = $totalPriceTaxExcl;
                        $objRoomTypeServiceProductOrderDetail->total_price_tax_incl = $totalPriceTaxIncl;
                        $objRoomTypeServiceProductOrderDetail->name = $product['name'];
                        $objRoomTypeServiceProductOrderDetail->quantity = $objRoomTypeServiceProductCartDetail->quantity;
                        $objRoomTypeServiceProductOrderDetail->save();

                        // update totals amount of order
                        $objOrder->total_products += (float)$totalPriceChangeTaxExcl;
                        $objOrder->total_products_wt += (float)$totalPriceChangeTaxIncl;

                        $objOrder->total_paid += Tools::ps_round((float)$totalPriceChangeTaxIncl, 2);
                        $objOrder->total_paid_tax_excl += Tools::ps_round((float)($totalPriceChangeTaxExcl), 2);
                        $objOrder->total_paid_tax_incl += Tools::ps_round((float)($totalPriceChangeTaxIncl), 2);
                    }
                }

                $objOrder->total_discounts += (float)abs($objCart->getOrderTotal(true, Cart::ONLY_DISCOUNTS));
                $objOrder->total_discounts_tax_excl += (float)abs($objCart->getOrderTotal(false, Cart::ONLY_DISCOUNTS));
                $objOrder->total_discounts_tax_incl += (float)abs($objCart->getOrderTotal(true, Cart::ONLY_DISCOUNTS));
                $objOrder->update();
            }
        }
    }

    // Does not contain the validations for the services since we are validating the products during the request formatting
    public function udpateServicesInRoom($services)
    {
        if (count($services)) {
            foreach ($services as $service) {
                $objHotelBookingDetail = new HotelBookingDetail((int) $service['id_htl_booking_detail']);
                $idRoomTypeServiceProductOrderDetail = $service['id_room_type_service_product_order_detail'];
                $quantity = $service['new_quantity'];
                if (Validate::isLoadedObject($objRoomTypeServiceProductOrderDetail = new RoomTypeServiceProductOrderDetail((int) $idRoomTypeServiceProductOrderDetail))) {
                    $objOrderDetail = new OrderDetail((int) $objRoomTypeServiceProductOrderDetail->id_order_detail);
                    $oldPriceTaxExcl = $objRoomTypeServiceProductOrderDetail->unit_price_tax_excl;
                    $oldPriceTaxIncl = $objRoomTypeServiceProductOrderDetail->unit_price_tax_incl;
                    $oldQuantity = $objRoomTypeServiceProductOrderDetail->quantity;
                    if ($quantity <= 0) {
                        $quantity = 1;
                    }

                    $objRoomTypeServiceProductOrderDetail->quantity = $quantity;
                    if ($objOrderDetail->product_price_calculation_method == Product::PRICE_CALCULATION_METHOD_PER_DAY) {
                        $quantity = $quantity * HotelHelper::getNumberOfDays(
                            $objHotelBookingDetail->date_from,
                            $objHotelBookingDetail->date_to
                        );
                    }
                    $objRoomTypeServiceProductOrderDetail->total_price_tax_excl = Tools::ps_round($objRoomTypeServiceProductOrderDetail->unit_price_tax_excl * $quantity, 6);
                    $objRoomTypeServiceProductOrderDetail->total_price_tax_incl = Tools::ps_round($objRoomTypeServiceProductOrderDetail->unit_price_tax_incl * $quantity, 6);
                    if ($objRoomTypeServiceProductOrderDetail->save()) {
                        $objOrder = new Order($objRoomTypeServiceProductOrderDetail->id_order);
                        $priceDiffTaxExcl = $objRoomTypeServiceProductOrderDetail->total_price_tax_excl - $oldPriceTaxExcl;
                        $priceDiffTaxIncl = $objRoomTypeServiceProductOrderDetail->total_price_tax_incl - $oldPriceTaxIncl;
                        $quantityDiff = $objRoomTypeServiceProductOrderDetail->quantity - $oldQuantity;

                        $objOrderDetail->product_quantity += $quantityDiff;
                        $objOrderDetail->total_price_tax_excl += $priceDiffTaxExcl;
                        $objOrderDetail->total_price_tax_incl += $priceDiffTaxIncl;
                        $objOrderDetail->unit_price_tax_excl = ($objOrderDetail->total_price_tax_excl / $objOrderDetail->product_quantity);
                        $objOrderDetail->unit_price_tax_incl = ($objOrderDetail->total_price_tax_incl / $objOrderDetail->product_quantity);
                        $objOrderDetail->updateTaxAmount($objOrder);

                        $objOrderDetail->update();

                        if ($objOrderDetail->id_order_invoice != 0) {
                            // values changes as values are calculated accoding to the quantity of the product by webkul
                            $objOrderInvoice = new OrderInvoice($objOrderDetail->id_order_invoice);
                            $objOrderInvoice->total_paid_tax_excl += $priceDiffTaxExcl;
                            $objOrderInvoice->total_paid_tax_incl += $priceDiffTaxIncl;
                            $objOrderInvoice->update();
                        }

                        $objOrder->total_paid_tax_excl += $priceDiffTaxExcl;
                        $objOrder->total_paid_tax_incl += $priceDiffTaxIncl;
                        $objOrder->total_paid += $priceDiffTaxIncl;

                        $objOrder->update();
                    }
                }
            }
        }
    }

    public function addDemandsInRoom($demands, $idHotelBooking)
    {
        if (Validate::isLoadedObject($objBookingDetail = new HotelBookingDetail((int) $idHotelBooking))) {
            if (count($demands)) {
                $objOrder = new Order($objBookingDetail->id_order);
                // set context currency So that we can get prices in the order currency
                $this->context->currency = new Currency($objOrder->id_currency);

                $vatAddress = new Address((int) $objOrder->id_address_tax);
                $idLang = (int) $objOrder->id_lang;
                $idProduct = $objBookingDetail->id_product;
                $objHtlBkDtl = new HotelBookingDetail();
                $objRoomDemandPrice = new HotelRoomTypeDemandPrice();
                foreach ($demands as $demand) {
                    $idGlobalDemand = $demand['id_global_demand'];
                    $idOption = $demand['id_option'];
                    $objBookingDemand = new HotelBookingDemands();
                    $objBookingDemand->id_htl_booking = $idHotelBooking;
                    $objGlobalDemand = new HotelRoomTypeGlobalDemand($idGlobalDemand, $idLang);
                    if ($idOption) {
                        $objOption = new HotelRoomTypeGlobalDemandAdvanceOption($idOption, $idLang);
                        $objBookingDemand->name = $objOption->name;
                    } else {
                        $idOption = 0;
                        $objBookingDemand->name = $objGlobalDemand->name;
                    }
                    $objBookingDemand->unit_price_tax_excl = HotelRoomTypeDemand::getPriceStatic(
                        $idProduct,
                        $idGlobalDemand,
                        $idOption,
                        0
                    );
                    $objBookingDemand->unit_price_tax_incl = HotelRoomTypeDemand::getPriceStatic(
                        $idProduct,
                        $idGlobalDemand,
                        $idOption,
                        1
                    );
                    $qty = 1;
                    if ($objGlobalDemand->price_calc_method == HotelRoomTypeGlobalDemand::WK_PRICE_CALC_METHOD_EACH_DAY) {
                        $numDays = $objHtlBkDtl->getNumberOfDays(
                            $objBookingDetail->date_from,
                            $objBookingDetail->date_to
                        );
                        if ($numDays > 1) {
                            $qty *= $numDays;
                        }
                    }

                    $objBookingDemand->total_price_tax_excl = $objBookingDemand->unit_price_tax_excl * $qty;
                    $objBookingDemand->total_price_tax_incl = $objBookingDemand->unit_price_tax_incl * $qty;

                    $objOrderDetail = new OrderDetail($objBookingDetail->id_order_detail);
                    // Update OrderInvoice of this OrderDetail
                    if ($objOrderDetail->id_order_invoice != 0) {
                        // values changes as values are calculated accoding to the quantity of the product by webkul
                        $objOrderInvoice = new OrderInvoice($objOrderDetail->id_order_invoice);
                        $objOrderInvoice->total_paid_tax_excl += $objBookingDemand->total_price_tax_excl;
                        $objOrderInvoice->total_paid_tax_incl += $objBookingDemand->total_price_tax_incl;
                        $objOrderInvoice->update();
                    }

                    // change order total
                    $objOrder->total_paid_tax_excl += $objBookingDemand->total_price_tax_excl;
                    $objOrder->total_paid_tax_incl += $objBookingDemand->total_price_tax_incl;
                    $objOrder->total_paid += $objBookingDemand->total_price_tax_incl;
                    $objBookingDemand->price_calc_method = $objGlobalDemand->price_calc_method;
                    $objBookingDemand->id_tax_rules_group = $objGlobalDemand->id_tax_rules_group;
                    if ($objBookingDemand->save()
                        && Validate::isLoadedObject($vatAddress)
                    ) {
                        $taxManager = TaxManagerFactory::getManager(
                            $vatAddress,
                            $objGlobalDemand->id_tax_rules_group
                        );
                        $taxCalc = $taxManager->getTaxCalculator();
                        $objBookingDemand->tax_computation_method = (int)$taxCalc->computation_method;
                        $objBookingDemand->tax_calculator = $taxCalc;
                        // Now save tax details of the extra demand
                        $objBookingDemand->setBookingDemandTaxDetails();
                    }
                }

                $objOrder->save();
            }
        }
    }

    public function removeDemandsFromRoom($demands)
    {
        if (count($demands)) {
            foreach ($demands as $demand) {
                $idBookingDemand = $demand['id_booking_demand'];
                if (Validate::isLoadedObject($objBookingDemand = new HotelBookingDemands($idBookingDemand))) {
                    if ($objBookingDemand->deleteBookingDemandTaxDetails($idBookingDemand)) {
                        if ($objBookingDemand->delete()) {
                            if (Validate::isLoadedObject($objBookingDetail = new HotelBookingDetail($objBookingDemand->id_htl_booking))) {
                                // change order total
                                $objOrder = new Order($objBookingDetail->id_order);
                                $objOrder->total_paid_tax_excl -= $objBookingDemand->total_price_tax_excl;
                                $objOrder->total_paid_tax_incl -= $objBookingDemand->total_price_tax_incl;
                                $objOrder->total_paid -= $objBookingDemand->total_price_tax_incl;
                                $objOrder->save();

                                $objOrderDetail = new OrderDetail($objBookingDetail->id_order_detail);
                                // Update OrderInvoice of this OrderDetail
                                if ($objOrderDetail->id_order_invoice != 0) {
                                    // values changes as values are calculated accoding to the quantity of the product by webkul
                                    $objOrder_invoice = new OrderInvoice($objOrderDetail->id_order_invoice);
                                    $objOrder_invoice->total_paid_tax_excl -= $objBookingDemand->total_price_tax_excl;
                                    $objOrder_invoice->total_paid_tax_incl -= $objBookingDemand->total_price_tax_incl;
                                    $objOrder_invoice->update();
                                }
                            }
                        }
                    }
                }
            }
        }

    }

    public function getBookingDetails($id_booking_detail)
    {
        // @todo: needs fixing
        $fields = array();
        $objOrder = new Order($id_booking_detail);
        if (!Validate::isLoadedObject($objOrder)) {
            $this->wsObject->setError(400, Tools::displayError('Please provide a valid booking id.'), 200);
        } else {
            $objCurrency = new Currency($objOrder->id_currency);
            $objBookingDetail = new HotelBookingDetail();
            $objBookingDemand = new HotelBookingDemands();
            $objRoomTypeServiceProductOrderDetail = new RoomTypeServiceProductOrderDetail();
            $objOrderReturn = new OrderReturn();
            $idHotel = HotelBookingDetail::getIdHotelByIdOrder($objOrder->id);
            $objHotelBranchInformation = new HotelBranchInformation($idHotel, Configuration::get('PS_LANG_DEFAULT'));
            $objOrderState = new OrderState($objOrder->current_state, Configuration::get('PS_LANG_DEFAULT'));

            $fields['id_booking'] = $objOrder->id;
            $fields['booking_reference'] = $objOrder->reference;
            $fields['current_status'] = $objOrderState->name;
            $fields['status_color'] = $objOrderState->color;
            $fields['payment_method'] = $objOrder->payment;
            $fields['total_services_with_tax'] = number_format($objOrder->getTotalProductsWithTaxes(false, false, Product::SERVICE_PRODUCT_WITH_ROOMTYPE, 0), 3, '.', '');
            $fields['total_services_without_tax'] = number_format($objOrder->getTotalProductsWithoutTaxes(false, false, Product::SERVICE_PRODUCT_WITH_ROOMTYPE, 0), 3, '.', '');
            $fields['total_rooms_with_tax'] = number_format($objOrder->getTotalProductsWithTaxes(false, true), 3, '.', '');
            $fields['total_rooms_without_tax'] = number_format($objOrder->getTotalProductsWithoutTaxes(false, true), 3, '.', '');
            $fields['total_amount_with_tax'] = number_format($objOrder->total_products_wt, 3, '.', '');
            $fields['total_amount_without_tax'] = number_format($objOrder->total_products, 3, '.', '');
            $fields['total_tax'] = number_format(($objOrder->total_paid_tax_incl - $objOrder->total_paid_tax_excl), 3, '.', '');
            $fields['total_discount'] = number_format($objOrder->total_discounts, 3, '.', '');
            $fields['total_due_amount'] = number_format(($objOrder->total_paid - $objOrder->total_paid_real), 3, '.', '');
            $currencyFields['id_currency'] = $objCurrency->id;
            $currencyFields['name'] = $objCurrency->name;
            $currencyFields['prefix'] = $objCurrency->prefix;
            $currencyFields['suffix'] = $objCurrency->suffix;
            $fields['has_refund_requests'] = false;
            if ($objOrderReturn->getOrderRefundRequestedBookings($objOrder->id, 0, 1)) {
                $fields['has_refund_requests'] = true;
            }

            $fields['total_amount'] = number_format($objOrder->total_paid, 3);
            $useTax = 0;
            $customer = $this->context->customer;
            if (Group::getPriceDisplayMethod($customer->id_default_group) == PS_TAX_INC) {
                $useTax = 1;
            }

            $fields['display_with_tax'] = $useTax;
            $this->output['booking'] = $fields;
            $this->output['booking']['hotel']['hotel_name'] = $objHotelBranchInformation->hotel_name;
            // $this->output['booking']['currency'] = $currencyFields;
            $fields = array();
            if ($orderDetailData = $objBookingDetail->getOrderFormatedBookinInfoByIdOrder($objOrder->id)) {
                foreach ($orderDetailData as $orderDetailKey => $orderData) {
                    $dateJoin = $orderData['id_product'].'_'.strtotime($orderData['date_from']).strtotime($orderData['date_to']);
                    if (!isset($fields[$dateJoin])) {
                        $fields[$dateJoin]['total_amount_with_tax'] = $orderData['total_price_tax_incl'];
                        $fields[$dateJoin]['total_amount_without_tax'] = $orderData['total_price_tax_excl'];
                        $fields[$dateJoin]['total_tax'] = $orderData['total_price_tax_incl'] - $orderData['total_price_tax_excl'];
                        $fields[$dateJoin]['total_rooms'] = 1;
                        $fields[$dateJoin]['name'] = $orderData['room_type_name'];
                        $fields[$dateJoin]['date_from'] = $orderData['date_from'];
                        $fields[$dateJoin]['date_to'] = $orderData['date_to'];
                        $fields[$dateJoin]['total_adults'] = $orderData['adults'];
                        $fields[$dateJoin]['total_children'] = $orderData['children'];
                    } else {
                        $fields[$dateJoin]['total_amount_with_tax'] += $orderData['total_price_tax_incl'];
                        $fields[$dateJoin]['total_amount_without_tax'] += $orderData['total_price_tax_excl'];
                        $fields[$dateJoin]['total_tax'] += $orderData['total_price_tax_incl'] - $orderData['total_price_tax_excl'];
                        $fields[$dateJoin]['total_rooms'] += 1;
                        $fields[$dateJoin]['total_adults'] += $orderData['adults'];
                        $fields[$dateJoin]['total_children'] += $orderData['children'];
                    }

                    $extraDemandsPrice = 0;
                    $fields[$dateJoin]['rooms'][$orderDetailKey]['id'] = $orderData['id_room'];
                    $refund_request = false;
                    $refundInfo = array();
                    $refundInfo['refunded'] = false;
                    $refundInfo['denied'] = false;
                    $refundInfo['refund_text'] = false;
                    $refundInfo['color'] = false;
                    if (isset($orderData['refund_info'])) {
                        $refund_request = true;
                        $refundInfo['refunded'] = $orderData['refund_info']['refunded'];
                        $refundInfo['denied'] = $orderData['refund_info']['denied'];
                        $refundInfo['refund_text'] = $orderData['refund_info']['name'];
                        $refundInfo['color'] = $orderData['refund_info']['color'];
                    }

                    $fields[$dateJoin]['rooms'][$orderDetailKey]['is_refunded'] = $orderData['is_refunded'];
                    $fields[$dateJoin]['rooms'][$orderDetailKey]['is_cancelled'] = $orderData['is_cancelled'];
                    $fields[$dateJoin]['rooms'][$orderDetailKey]['is_refund_requested'] = $refund_request;
                    $fields[$dateJoin]['rooms'][$orderDetailKey]['refund_info'] = $refundInfo;
                    $fields[$dateJoin]['rooms'][$orderDetailKey]['id_hotel_booking'] = $orderData['id'];
                    $fields[$dateJoin]['rooms'][$orderDetailKey]['adults'] =  $orderData['adults'];
                    $fields[$dateJoin]['rooms'][$orderDetailKey]['children'] =  $orderData['children'];
                    if ($extraDemands = $objBookingDemand->getRoomTypeBookingExtraDemands(
                        $orderData['id_order'],
                        $orderData['id_product'],
                        $orderData['id_room'],
                        $orderData['date_from'],
                        $orderData['date_to'],
                        0,
                        0,
                        $useTax
                    )) {
                        foreach ($extraDemands as $extraDemand) {
                            $demand = array();
                            $demand['name'] = $extraDemand['name'];
                            $demand['id'] = $extraDemand['id_booking_demand'];
                            $demand['quantity'] = 1;
                            if ($useTax) {
                                $demand['total_price'] = $extraDemand['total_price_tax_incl'];
                                $demand['unit_price'] = $extraDemand['unit_price_tax_incl'];
                            } else {
                                $demand['total_price'] = $extraDemand['total_price_tax_excl'];
                                $demand['unit_price'] = $extraDemand['unit_price_tax_excl'];
                            }

                            $demand['per_night'] = false;
                            if ($extraDemand['price_calc_method'] == HotelRoomTypeGlobalDemand::WK_PRICE_CALC_METHOD_EACH_DAY) {
                                $demand['per_night'] = true;
                            }

                            $extraDemandsPrice += $demand['total_price'];
                            $fields[$dateJoin]['rooms'][$orderDetailKey]['facilities'][] = $demand;
                        }
                    }

                    $fields[$dateJoin]['extra_demands'] = $extraDemandsPrice;
                    $additionalServicePrice = 0;
                    if ($additionalServices = $objRoomTypeServiceProductOrderDetail->getroomTypeServiceProducts(
                        $orderData['id_order'],
                        0,
                        0,
                        $orderData['id_product'],
                        $orderData['date_from'],
                        $orderData['date_to'],
                        $orderData['id_room'],
                        0,
                        $useTax
                    )) {
                        foreach ($additionalServices as $additionalService) {
                            foreach ($additionalService['additional_services'] as $service) {
                                $services = array();
                                $services['name'] = $service['name'];
                                $services['with_quantity'] = $service['allow_multiple_quantity'];
                                $services['quantity'] = $service['quantity'];

                                if ($useTax) {
                                    $services['unit_price'] = $service['total_price_tax_incl'] / $services['quantity'];
                                    $services['total_price'] = $service['total_price_tax_incl'];
                                } else {
                                    $services['unit_price'] = $service['total_price_tax_excl'] / $services['quantity'];
                                    $services['total_price'] = $service['total_price_tax_excl'];
                                }

                                $objProduct = new Product($service['id_product']);
                                $services['per_night'] = false;
                                if ($objProduct->price_calculation_method == HotelRoomTypeGlobalDemand::WK_PRICE_CALC_METHOD_EACH_DAY) {
                                    $services['per_night'] = true;
                                }

                                $additionalServicePrice += $services['total_price'];
                                $fields[$dateJoin]['rooms'][$orderDetailKey]['services'][] = $services;
                            }
                        }
                    }

                    $fields[$dateJoin]['additional_services'] = $additionalServicePrice;
                }
            }
        }
    }

    public function createGuestForBooking()
    {
        $guest = new Guest();
        $guest->id_operating_system = 7; // For Android Device
        $guest->id_web_browser = 1; // For Other(Opera)
        $guest->mobile_theme = 1; // For Mobile device
        $guest->save();
        if ($guest->id) {
            return $guest->id;
        }

        return 0;
    }

    public function validateBookingFields($fields)
    {
        $this->error_msg = '';
        if (!isset($fields['currency'])
            || !$fields['currency']
            || !Currency::getIdByIsoCode($fields['currency'])
            || (!Validate::isLoadedObject((new Currency(Currency::getIdByIsoCode($fields['currency'])))))
        ) {
            $this->error_msg = Tools::displayError('Please provide valid currency for the booking');
        } else if (!isset($fields['guest_detail']['firstname'])
            || empty($fields['guest_detail']['firstname'])
        ) {
            $this->error_msg = Tools::displayError('Please provide first name for the booking');
        } else if (!isset($fields['guest_detail']['lastname'])
            || empty($fields['guest_detail']['lastname'])
        ) {
            $this->error_msg = Tools::displayError('Please provide last name for the booking');
        } else if (!isset($fields['guest_detail']['email'])
            || empty($fields['guest_detail']['email'])
        ) {
            $this->error_msg = Tools::displayError('Please provide email for the booking');
        } else if (!isset($fields['guest_detail']['phone'])
            || empty($fields['guest_detail']['phone'])
        ) {
            $this->error_msg = Tools::displayError('Please provide phone for the booking');
        }

        if (!$this->error_msg && $this->error_msg == '') {
            return true;
        }

        return false;

    }

    public function getRequestParams($head = false)
    {
        $putresource = fopen('php://input', 'r');
        $inputXML = '';
        while ($putData = fread($putresource, 1024)) {
            $inputXML .= $putData;
        }

        fclose($putresource);
        // If xml
        if (simplexml_load_string($inputXML)) {
            if (isset($inputXML) && strncmp($inputXML, 'xml=', 4) == 0) {
                $inputXML = Tools::substr($inputXML, 4);
            }
        } else {
            // If input type is json
            $array = json_decode($inputXML, true);
            if (isset($array['json']) && $array['json'] && ($head ? isset($array[$head]) : true)) {
                return ($head ? $array[$head] : $array);
            } else {
                WebserviceRequest::getInstance()->setError(500, 'Invalid request.', 127);
                return;
            }
        }

        try {
            $xml = new SimpleXMLElement($inputXML);
        } catch (Exception $error) {
            WebserviceRequest::getInstance()->setError(500, 'XML error : '.$error->getMessage()."\n".'XML length : '.Tools::strlen($inputXML)."\n".'Original XML : '.$inputXML, 127);

            return;
        }

        $xmlEntities = $xml->children();
        // Convert multi-dimention xml into an array
        $array = json_decode(json_encode($xmlEntities), true);

        return ($head ? $array[$head] : $array);
    }

}
