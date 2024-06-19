let region_id = document.getElementById('region_id')
let district_id = document.getElementById('district_id')
let region = document.getElementById('region')
let district = document.getElementById('district')
let current_region_index = -1
let edit_changed = false

function addOption(item, index){
    let region_option = document.createElement('option')
    region_option.value = index
    region_option.text = item.name
    if(localStorage.getItem('region_id') != undefined && localStorage.getItem('region_id') != null && localStorage.getItem('region_id') == index){
        region_option.selected = true
        edit_changed = true
    }else if(edit_changed == false){
        if(current_region == item.id){
            region_option.selected = true
            current_region_index = index
        }
    }
    region_id.add(region_option)
}
$(document).ready(function () {
    $.ajax({
        url:"/../api/get-districts",
        type:'GET',
        success: function (data) {
            data.data.forEach(addOption)
            if(page == false && localStorage.getItem('region_id') != undefined && localStorage.getItem('region_id') != null &&
                localStorage.getItem('district_id') != undefined && localStorage.getItem('district_id') != null) {
                districts_ = data.data[localStorage.getItem('region_id')].cities
                Object.keys(districts_).forEach(function (key) {
                    let district_selected_option = document.createElement('option')
                    district_selected_option.value = key
                    district_selected_option.text = districts_[key].name
                    if (localStorage.getItem('district_id') != undefined && localStorage.getItem('district_id') != null && localStorage.getItem('district_id') == key) {
                        district_selected_option.selected = true
                    }
                    district_id.add(district_selected_option)
                })
            }else if(page == true){
                if(current_region_index != -1){
                    districts_ = data.data[current_region_index].cities
                    Object.keys(districts_).forEach(function (key) {
                        let district_selected_option = document.createElement('option')
                        district_selected_option.value = key
                        district_selected_option.text = districts_[key].name
                        if (districts_[key].id == current_district) {
                            district_selected_option.selected = true
                        }
                        district_id.add(district_selected_option)
                    })
                }
            }
            region_id.addEventListener('change', function () {
                localStorage.setItem('region_id', region_id.value)
                district_id.innerHTML = ""
                let district_option_disabled = document.createElement('option')
                district_option_disabled.text = "Select district or city of "+ data.data[region_id.value].region
                district_option_disabled.disabled = true
                district_option_disabled.selected = true
                district_option_disabled.value = ''
                district_id.add(district_option_disabled)
                districts = data.data[region_id.value].cities
                Object.keys(districts).forEach(function (key) {
                    let district_option = document.createElement('option')
                    district_option.text = districts[key].name
                    district_option.value = key
                    district_id.add(district_option)
                })
            })
            district_id.addEventListener('change', function () {
                localStorage.setItem('region', data.data[region_id.value].id)
                localStorage.setItem('district', data.data[region_id.value].cities[district_id.value].id)
                localStorage.setItem('district_id', district_id.value)
                region.value = data.data[region_id.value].id
                district.value = data.data[region_id.value].cities[district_id.value].id
            })
        }
    });
});
edit_changed = false
