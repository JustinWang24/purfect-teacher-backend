function Location() {
    this.items	= itemall;
}

Location.prototype.find	= function(id) {
    if(typeof(this.items[id]) == "undefined")
        return false;
    return this.items[id];
}

Location.prototype.fillOption	= function(el_id , loc_id , selected_id) {
    var el	= $('#'+el_id);

    var json	= this.find(loc_id);

    if (json) {
        var index	= 1;
        var selected_index	= 0;
        $.each(json , function(k , v) {
            var option	= '<option value="'+k+'">'+v+'</option>';
            el.append(option);

            if (k == selected_id) {
                selected_index	= index;
            }

            index++;
        })
        //el.attr('selectedIndex' , selected_index);
        el[0].selectedIndex = selected_index;
    }
}

function showLocation(province , city , town) {

    var loc	= new Location();
    var title	= ['请选择' , '请选择' , '请选择'];
    $.each(title , function(k , v) {
        title[k]	= '<option value="">'+v+'</option>';
    })

    $('#cityid').append(title[0]);
    $('#countryid').append(title[1]);
    $('#circleid').append(title[2]);


    $('#cityid').change(function() {
        $('#countryid').empty();
        $('#countryid').append(title[1]);
        loc.fillOption('countryid' , '0,'+$('#cityid').val());
        $('#circleid').empty();
        $('#circleid').append(title[2]);
    })

    $('#countryid').change(function() {
        $('#circleid').empty();
        $('#circleid').append(title[2]);
        loc.fillOption('circleid' , '0,' + $('#cityid').val() + ',' + $('#countryid').val());
        //$('input[@name=location_id]').val($(this).val());
    })

    if (province) {
        loc.fillOption('cityid' , '0' , province);

        if (city) {
            loc.fillOption('countryid' , '0,'+province , city);

            if (town) {
                loc.fillOption('circleid' , '0,'+province+','+city , town);
            }
        }

    } else {
        loc.fillOption('cityid' , '0');
    }

}