let coupon_type = document.getElementById('coupon_type')
let coupon_percent = document.getElementById('coupon_percent')
let coupon_price = document.getElementById('coupon_price')
let coupon_price_input = document.getElementById('coupon_price_input')
let coupon_percent_input = document.getElementById('coupon_percent_input')

let coupon__type = document.getElementById('coupon__type')
let coupon_quantity = document.getElementById('coupon_quantity')
let coupon_number = document.getElementById('coupon_number')
let coupon_quantity_input = document.getElementById('coupon_quantity_input')
let coupon_number_input = document.getElementById('coupon_number_input')

if(coupon_percent_value != ''){
    if(coupon_percent.classList.contains('display-none')){
        coupon_percent.classList.remove('display-none')
    }
    if(!coupon_price.classList.contains('display-none')){
        coupon_price.classList.add('display-none')
    }
}else if(coupon_price_value != ''){
    if(coupon_price.classList.contains('display-none')){
        coupon_price.classList.remove('display-none')
    }
    if(!coupon_percent.classList.contains('display-none')){
        coupon_percent.classList.add('display-none')
    }
}

coupon_type.addEventListener('change', function () {
    if(coupon_type.value == 'percent'){
        if(coupon_percent.classList.contains('display-none')){
            coupon_percent.classList.remove('display-none')
        }
        if(!coupon_price.classList.contains('display-none')){
            coupon_price.classList.add('display-none')
        }
        coupon_percent_input.value = ''
    }else if(coupon_type.value == 'price'){
        if(coupon_price.classList.contains('display-none')){
            coupon_price.classList.remove('display-none')
        }
        if(!coupon_percent.classList.contains('display-none')){
            coupon_percent.classList.add('display-none')
        }
        coupon_price_input.value = ''
    }
})

if(coupon_quantity_value != ''){
    if(coupon_quantity.classList.contains('display-none')){
        coupon_quantity.classList.remove('display-none')
    }
    if(!coupon_number.classList.contains('display-none')){
        coupon_number.classList.add('display-none')
    }
}else if(coupon_number_value != ''){
    if(coupon_number.classList.contains('display-none')){
        coupon_number.classList.remove('display-none')
    }
    if(!coupon_quantity.classList.contains('display-none')){
        coupon_quantity.classList.add('display-none')
    }
}

coupon__type.addEventListener('change', function () {
    if(coupon__type.value == 'quantity'){
        if(coupon_quantity.classList.contains('display-none')){
            coupon_quantity.classList.remove('display-none')
        }
        if(!coupon_number.classList.contains('display-none')){
            coupon_number.classList.add('display-none')
        }
        coupon_quantity_input.value = ''
    }else if(coupon__type.value == 'number'){
        if(coupon_number.classList.contains('display-none')){
            coupon_number.classList.remove('display-none')
        }
        if(!coupon_quantity.classList.contains('display-none')){
            coupon_quantity.classList.add('display-none')
        }
        coupon_number_input.value = ''
    }
})
