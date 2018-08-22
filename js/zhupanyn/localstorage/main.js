function Localstorage() {
    //this.name = name;
    this.obj_prod = null;
    this.page_size = null;
    this.product_id = null;
    this.time = null;
    this.domain = null;
}

//Localstorage.prototype.setObjProd = function(product) {
//    this.obj_prod = product;
//}
//
//Localstorage.prototype.setPageSize = function(page_size) {
//    this.page_size = product;
//}

Localstorage.prototype.getJsonStorage = function () {

    /*var products = [];
    var input_storage = localStorage.getItem("zhupanyn_localstorage_product_viewed");
    if (input_storage != null) {
        products = JSON.parse(input_storage);
    }
    return products*/

    var storage = localStorage.getItem("zhupanyn_localstorage_product_viewed");
    if (storage != null) {
        storage = JSON.parse(storage);
    }
    return storage;
};

Localstorage.prototype.setJsonStorage = function (storage) {

    var json_str = JSON.stringify(storage);
    localStorage.setItem("zhupanyn_localstorage_product_viewed", json_str);
};

Localstorage.prototype.prepareStorageObject = function (products, end_time) {

    if (end_time === undefined) {
        end_time = null;
    }
    if (products === undefined) {
        products = [];
    }

    var storage = {
        end_time: end_time,
        products: products
    };

    return storage;
};

Localstorage.prototype.currentStorage = function (storage, url) {

    if (storage == null) {
        storage = this.prepareStorageObject();
    }

    var cookie = Mage.Cookies.get('user_login');
    if (cookie == null) {

        var diff = 1;
        if (storage.end_time != null) {
            var now = new Date();
            var end_time = new Date();
            end_time.setTime(storage.end_time);
            diff = now - end_time;
        }

        if (diff > 0) {

            $j.ajax({
                url: url,
                data: {},
                type: 'POST',
                dataType: "json"
            }).done(function (data) {

                storage = zhupanyn_localstorage.prepareStorageObject( data, zhupanyn_localstorage.getExpireTime() );
                zhupanyn_localstorage.doAfterAjax(storage, true);

                //renderProducts(data);
            }).fail(function () {
                //$j('#recently-viewed-items-test').html('Помилка!');
            });

        } else {
            zhupanyn_localstorage.doAfterAjax(storage, false);
        }
    } else {

        $j.ajax({
            url: url,
            data: {},
            type: 'POST',
            dataType: "json"
        }).done(function (data) {

            storage = zhupanyn_localstorage.prepareStorageObject( data, zhupanyn_localstorage.getExpireTime() );

            //Mage.Cookies.clear('user_login');
            var erase = new Date();
            erase.setDate(erase.getDate() - 1);
            var strstrstr = "user_login=0; expires=" + erase.toUTCString() + "; path=/; domain=" + zhupanyn_localstorage.domain;
            document.cookie = "user_login=0; expires=" + erase.toUTCString() + "; path=/; domain=" + zhupanyn_localstorage.domain;

            zhupanyn_localstorage.doAfterAjax(storage, true);

            //renderProducts(data);
        }).fail(function () {
            //$j('#recently-viewed-items-test').html('Помилка!');
        });

    }

    //return storage;
};

Localstorage.prototype.getExpireTime = function () {

    var expire = new Date();
    expire.setHours(expire.getHours() + parseInt(this.time,10));
    return expire.getTime();

}

Localstorage.prototype.doAfterAjax = function (storage, need_save_storage) {

    if (this.obj_prod == null) {
        if (storage.products.length != 0) {
            this.renderProducts(storage.products);
            $j("#recently-viewed-wrapper").show();

            if ( need_save_storage ){
                this.setJsonStorage(storage);
            }
        }
    } else {
        var product_id_exist = false;
        for (var i = 0; i < storage.products.length; i++) {
            if (storage.products[i].product_id == product_id) {
                product_id_exist = true;
            }
        }
        if (!product_id_exist) {
            storage.products.unshift(this.obj_prod);
            if (storage.products.length > this.page_size) {
                storage.products.pop();
            }
        }
        this.setJsonStorage(storage);
    }

};

Localstorage.prototype.renderProducts = function (product_array) {

    var ol = $j("#recently-viewed-items");

    for (var i = 0; i < product_array.length; i++) {

        var li = $j('<li/>').addClass('item').appendTo(ol);
        var img_link = $j('<a/>').prop('href', product_array[i].product_url).appendTo(li);
        var img_span = $j('<span/>').addClass('product-image').appendTo(img_link);
        var img = $j('<img/>').attr({
            'width': '50',
            'height': '50',
            'src': product_array[i].img_src,
            'alt': product_array[i].img_alt
        }).appendTo(img_span);

        var name_div = $j('<div/>').addClass('product-details').appendTo(li);
        var name_p = $j('<p/>').addClass('product-name').appendTo(name_div);
        var name_link = $j('<a/>').prop('href', product_array[i].product_url).text(product_array[i].product_name).appendTo(name_p);
    }
}

var zhupanyn_localstorage = new Localstorage();

