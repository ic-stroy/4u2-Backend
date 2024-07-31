<?php

namespace App;

class Constants {

    //active or not active

    const ACTIVE = 1;
    const NOT_ACTIVE = 0;

   // Order    type_ids
   const BASKED = 1;
   const ORDERED = 2;
   const PERFORMED = 3;
   const CANCELLED = 4;
   const ORDER_DELIVERED = 5;
    const READY_FOR_PICKUP = 6;
    const ACCEPTED_BY_RECIPIENT = 7;

   //Payment method
    const CASH = 1;
    const ONLINE = 2;

    // coupon type
    const TO_ORDER_COUNT = 0; //maslan 10 ta orderga
    const FOR_ORDER_NUMBER = 1; // masalan 10 - orderga  // agar typy null bosa demak coupon order countga berilmagan

    //Order detail companyalar ga notificatsiya borganda ular qabul qilishi yoki kechiktrishi

    const ORDER_DETAIL_BASKET = 1;
    const ORDER_DETAIL_ORDERED = 2;
    const ORDER_DETAIL_PERFORMED = 3;
    const ORDER_DETAIL_PERFORMED_BY_SUPERADMIN = 4;
    const ORDER_DETAIL_CANCELLED = 5;
    const ORDER_DETAIL_ACCEPTED_BY_RECIPIENT = 6;

   // 1 basked 2 ordered 3 accepted 4 on_the_way 5 finished

    //Personal info

    const MALE = 1;
    const FEMALE = 2;

    //User roles

    const ADMIN = 1;
    const USER = NULL;

}
