let coupon_type = document.getElementById('coupon_type')
let coupon_percent = document.getElementById('coupon_percent')
let coupon_price = document.getElementById('coupon_price')
let coupon_price_input = document.getElementById('coupon_price_input')
let coupon_percent_input = document.getElementById('coupon_percent_input')

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
