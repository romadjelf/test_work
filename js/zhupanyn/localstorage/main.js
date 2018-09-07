function Localstorage() {

    this.productObject = null;
    this.pageSize = null;
    this.time = null;
    this.domain = null;
};

Localstorage.prototype.getJsonStorage = function () {

    var storage = localStorage.getItem("zhupanyn_localstorage_product_viewed");
    if (storage != null) {
        storage = JSON.parse(storage);
    }
    return storage;
};

Localstorage.prototype.setJsonStorage = function (storage) {

    var jsonStr = JSON.stringify(storage);
    localStorage.setItem("zhupanyn_localstorage_product_viewed", jsonStr);
};

Localstorage.prototype.deleteJsonStorage = function () {

    localStorage.removeItem("zhupanyn_localstorage_product_viewed");
};

Localstorage.prototype.prepareStorageObject = function (products, endTime) {

    if (endTime === undefined) {
        endTime = null;
    }
    if (products === undefined) {
        products = [];
    }

    var storage = {
        endTime: endTime,
        products: products
    };

    return storage;
};

Localstorage.prototype.currentStorage = function (url) {

    var storage = this.getJsonStorage();

    if (storage == null) {
        storage = this.prepareStorageObject();
    }

    var cookie = Mage.Cookies.get('user_login');
    if (cookie == null) {

        var diff = 1;
        if (storage.endTime != null) {
            var now = new Date();
            var endTime = new Date();
            endTime.setTime(storage.endTime);
            diff = now - endTime;
        }

        if (diff > 0) {

            $j.ajax({
                url: url,
                data: {},
                type: 'POST',
                dataType: "json"
            }).done(function (data) {

                storage = ZhupanynLocalstorage.prepareStorageObject( data, ZhupanynLocalstorage.getExpireTime() );
                ZhupanynLocalstorage.doAfterAjax(storage, true);

            }).fail(function () {

            });

        } else {
            ZhupanynLocalstorage.doAfterAjax(storage, false);
        }

    } else if (cookie == 1) {

        $j.ajax({
            url: url,
            data: {},
            type: 'POST',
            dataType: "json"
        }).done(function (data) {

            storage = ZhupanynLocalstorage.prepareStorageObject( data, ZhupanynLocalstorage.getExpireTime() );
            ZhupanynLocalstorage.clearUserLoginCookie();
            ZhupanynLocalstorage.doAfterAjax(storage, true);

        }).fail(function () {

        });

    } else {
        this.clearUserLoginCookie();
        this.deleteJsonStorage();
    }
};

Localstorage.prototype.getExpireTime = function () {

    var expire = new Date();
    expire.setHours(expire.getHours() + parseInt(this.time,10));
    return expire.getTime();

};

Localstorage.prototype.doAfterAjax = function (storage, needSaveStorage) {

    if (this.productObject == null) {
        if (storage.products.length != 0) {
            this.renderProducts(storage.products);
            $j("#recently-viewed-wrapper").show();

            if ( needSaveStorage ){
                this.setJsonStorage(storage);
            }
        }
    } else {
        var productIdExist = false;
        for (var i = 0; i < storage.products.length; i++) {
            if (storage.products[i].product_id == this.productObject.product_id) {
                productIdExist = true;
            }
        }
        if (!productIdExist) {
            storage.products.unshift(this.productObject);
            if (storage.products.length > this.pageSize) {
                storage.products.pop();
            }
        }
        this.setJsonStorage(storage);
    }

};

Localstorage.prototype.renderProducts = function (productArray) {

    var ol = $j("#recently-viewed-items");

    for (var i = 0; i < productArray.length; i++) {

        var li = $j('<li/>').addClass('item').appendTo(ol);
        var imgLink = $j('<a/>').prop('href', productArray[i].product_url).appendTo(li);
        var imgSpan = $j('<span/>').addClass('product-image').appendTo(imgLink);
        var img = $j('<img/>').attr({
            'width': '50',
            'height': '50',
            'src': productArray[i].img_src,
            'alt': productArray[i].img_alt
        }).appendTo(imgSpan);

        var nameDiv = $j('<div/>').addClass('product-details').appendTo(li);
        var nameP = $j('<p/>').addClass('product-name').appendTo(nameDiv);
        var nameLink = $j('<a/>').prop('href', productArray[i].product_url).text(productArray[i].product_name).appendTo(nameP);
    }
};

Localstorage.prototype.clearUserLoginCookie = function () {

    var erase = new Date();
    erase.setDate(erase.getDate() - 1);
    document.cookie = "user_login=0; expires=" + erase.toUTCString() + "; path=/; domain=" + this.domain;

};

var ZhupanynLocalstorage = new Localstorage();

