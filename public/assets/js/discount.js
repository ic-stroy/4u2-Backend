let subcategory_exists = document.getElementById('subcategory_exists')
let subsubcategory_exists = document.getElementById('subsubcategory_exists')
let product_exists = document.getElementById('product_exists')

let category_id = document.getElementById('category_id')
let subcategory_id = document.getElementById('subcategory_id')
let subsubcategory_id = document.getElementById('subsubcategory_id')
let product_id = document.getElementById('product_id')

function discountAddOption(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    if(discount_subcategory_id != 'two'){
        if(item.id == discount_subcategory_id){
            option.selected = true
        }
    }
    subcategory_id.add(option)
}
if(discount_subcategory_id != '' && discount_category_id != 'two' && discount_category_id != ''){
    subcategory_id.innerHTML = ""
    product_id.innerHTML = ""
    subsubcategory_id.innerHtml = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../api/subcategory/${discount_category_id}`,
            type:'GET',
            success: function (data) {
                if(subcategory_exists.classList.contains('display-none')){
                    subcategory_exists.classList.remove('display-none')
                }
                let disabled_option = document.createElement('option')
                disabled_option.text = text_select_product
                disabled_option.disabled = true
                subcategory_id.add(disabled_option)
                let all_subcategories = document.createElement('option')
                all_subcategories.text = text_all_subcategory_products
                all_subcategories.value = "all"
                subcategory_id.add(all_subcategories)
                data.data.forEach(discountAddOption)
            },
            error: function (e) {
                if(!subcategory_exists.classList.contains('display-none')){
                    subcategory_exists.classList.add('display-none')
                }
            }
        })
    })
}else{
    let all_subcategories = document.createElement('option')
    all_subcategories.text = text_all_subcategory_products
    all_subcategories.value = "all"
    all_subcategories.selected = true
    subcategory_id.add(all_subcategories)
}
function discountSubAddOption(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    if(discount_subcategory_id != 'two'){
        if(item.id == discount_subcategory_id){
            option.selected = true
        }
    }
    if(item.id == discount_subsubcategory_id){
        option.selected = true
    }
    subsubcategory_id.add(option)
}
if(discount_subsubcategory_id != '' && discount_subcategory_id != 'two' && discount_subcategory_id != ''){
    subcategory_id.innerHTML = ""
    product_id.innerHTML = ""
    subsubcategory_id.innerHTML = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../api/subcategory/${discount_subcategory_id}`,
            type:'GET',
            success: function (data) {
                if(subcategory_exists.classList.contains('display-none')){
                    subcategory_exists.classList.remove('display-none')
                }
                if(subsubcategory_exists.classList.contains('display-none')){
                    subsubcategory_exists.classList.remove('display-none')
                }
                let disabled_option = document.createElement('option')
                disabled_option.text = text_select_product
                disabled_option.disabled = true
                subsubcategory_id.add(disabled_option)
                let all_subsubcategories = document.createElement('option')
                all_subsubcategories.text = text_all_subsubcategory_products
                all_subsubcategories.value = "all"
                subsubcategory_id.add(all_subsubcategories)
                data.data.forEach(discountSubAddOption)
            },
            error: function (e) {
                if(!subsubcategory_exists.classList.contains('display-none')){
                    subsubcategory_exists.classList.add('display-none')
                }
            }
        })
    })
}else{
    let all_subsubcategories = document.createElement('option')
    all_subsubcategories.text = text_all_subsubcategory_products
    all_subsubcategories.value = "all"
    all_subsubcategories.selected = true
    subsubcategory_id.add(all_subsubcategories)
}
function discountAddOptionToProduct(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    if(discount_product_id != 'two'){
        if(item.id == discount_product_id){
            option.selected = true
        }
    }
    product_id.add(option)
}
if(discount_product_id != undefined && discount_product_id != '' && discount_product_id != null
    && discount_subsubcategory_id != 'two' && discount_subsubcategory_id != '' && discount_subsubcategory_id != 'all'){
    product_id.innerHTML = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../api/get-products-by-category?category_id=${discount_subsubcategory_id}`,
            type:'GET',
            success: function (data) {
                if(product_exists.classList.contains('display-none')){
                    product_exists.classList.remove('display-none')
                }
                let disabled_option = document.createElement('option')
                disabled_option.text = text_select_product
                disabled_option.disabled = true
                product_id.add(disabled_option)
                let all_products = document.createElement('option')
                all_products.text = text_all_products
                all_products.value = "all"
                product_id.add(all_products)
                data.data[0].products.forEach(discountAddOptionToProduct)
            },
            error: function (e) {
                if(!product_exists.classList.contains('display-none')){
                    product_exists.classList.add('display-none')
                }
            }
        })
    })
}else{
    let all_products = document.createElement('option')
    all_products.text = text_all_products
    all_products.value = "all"
    all_products.selected = true
    product_id.add(all_products)
}

function addOption(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    subcategory_id.add(option)
}

category_id.addEventListener('change', function () {
    subcategory_id.innerHTML = ""
    subsubcategory_id.innerHTML = ""
    product_id.innerHTML = ""
    if(!product_exists.classList.contains('display-none')){
        product_exists.classList.add('display-none')
    }
    if(!subsubcategory_exists.classList.contains('display-none')){
        subsubcategory_exists.classList.add('display-none')
    }
    $(document).ready(function () {
        if(subcategory_id.value != '') {
            $.ajax({
                url: `/../api/subcategory/${category_id.value}`,
                type: 'GET',
                success: function (data) {
                    console.log(data)
                    if (subcategory_exists.classList.contains('display-none')) {
                        subcategory_exists.classList.remove('display-none')
                    }
                    let disabled_option = document.createElement('option')
                    disabled_option.text = text_select_sub_category
                    disabled_option.selected = true
                    disabled_option.disabled = true
                    subcategory_id.add(disabled_option)
                    let all_products = document.createElement('option')
                    all_products.text = text_all_products
                    all_products.value = "all"
                    subcategory_id.add(all_products)
                    data.data.forEach(addOption)
                },
                error: function (e) {
                    if (!subcategory_exists.classList.contains('display-none')) {
                        subcategory_exists.classList.add('display-none')
                    }
                    if (!product_exists.classList.contains('display-none')) {
                        product_exists.classList.add('display-none')
                    }
                }
            })
        }
    })
})
function addOptionToSubSubCategory(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    subsubcategory_id.add(option)
}
subcategory_id.addEventListener('change', function () {
    subsubcategory_id.innerHTML = ""
    product_id.innerHTML = ""
    if(!product_exists.classList.contains('display-none')){
        product_exists.classList.add('display-none')
    }
    $(document).ready(function () {
        if(subcategory_id.value != '' && subcategory_id.value != 'all') {
            $.ajax({
                url: `/../api/subcategory/${subcategory_id.value}`,
                type: 'GET',
                success: function (data) {
                    if (subsubcategory_exists.classList.contains('display-none')) {
                        subsubcategory_exists.classList.remove('display-none')
                    }
                    console.log(data)
                    let disabled_option = document.createElement('option')
                    disabled_option.text = text_select_product
                    disabled_option.selected = true
                    disabled_option.disabled = true
                    subsubcategory_id.add(disabled_option)
                    let sub_sub_category = document.createElement('option')
                    sub_sub_category.text = text_all_subsubcategory_products
                    sub_sub_category.value = "all"
                    subsubcategory_id.add(sub_sub_category)
                    data.data.forEach(addOptionToSubSubCategory)
                },
                error: function (e) {
                    if (!subsubcategory_exists.classList.contains('display-none')) {
                        subsubcategory_exists.classList.add('display-none')
                    }
                }
            })
        }
    })
})

function addOptionToProduct(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    product_id.add(option)
}
subsubcategory_id.addEventListener('change', function () {
    product_id.innerHTML = ""
    $(document).ready(function () {
        if(subsubcategory_id.value != 'all' && subsubcategory_id.value != ''){
            $.ajax({
                url:`/../api/get-products-by-category?category_id=${subsubcategory_id.value}`,
                type:'GET',
                success: function (data) {
                    console.log({'product':data})
                    if(product_exists.classList.contains('display-none')){
                        product_exists.classList.remove('display-none')
                    }
                    let disabled_option = document.createElement('option')
                    disabled_option.text = text_select_product
                    disabled_option.selected = true
                    disabled_option.disabled = true
                    product_id.add(disabled_option)
                    let all_products = document.createElement('option')
                    all_products.text = text_all_products
                    all_products.value = "all"
                    product_id.add(all_products)
                    data.data[0].products.forEach(addOptionToProduct)
                },
                error: function (e) {
                    if(!product_exists.classList.contains('display-none')){
                        product_exists.classList.add('display-none')
                    }
                }
            })
        }

    })
})
