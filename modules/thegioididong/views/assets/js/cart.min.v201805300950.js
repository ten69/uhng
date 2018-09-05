function POSTAjax(n, t, i, r, u, f) {
    $.ajax({
        async: f,
        url: n,
        data: t,
        type: "POST",
        cache: !1,
        beforeSend: function() {
            i()
        },
        success: function(n) {
            r(n)
        },
        error: function() {
            u()
        }
    })
}
function BeforeSendAjax() {
    $("#dlding").show()
}
function EndSendAjax() {
    $("#dlding").hide()
}
function ErrorAjax() {
    $("#dlding").hide()
}
function SuggestSearch(n, t, i, r) {
    var u, e, f;
    if (typeof r == "undefined" && (r = !1),
    u = $(t) === undefined ? "" : $(t).val(),
    u = u.trim().toLowerCase(),
    e = $(t).parents("form"),
    f = e.find(".wrap-suggestion"),
    n.which == 40 || n.which == 38)
        return n.preventDefault(),
        UpDownSuggest(n.which),
        !1;
    if (n.which == 13) {
        if ($("ul.wrap-suggestion li.selected").length > 0)
            return location.href = $("ul.wrap-suggestion li.selected a").attr("href"),
            n.preventDefault(),
            !1;
        if (u.length < 2) {
            f.hide();
            return
        }
    }
    if (!r) {
        clearTimeout(timmer);
        timmer = setTimeout(function() {
            SuggestSearch(n, t, i, !0)
        }, 300);
        return
    }
    if (u.length < 2) {
        f.hide();
        return
    }
    $.ajax({
        url: "/aj/CommonV3/SuggestSearch",
        type: "GET",
        data: {
            keyword: u,
            categoryID: i
        },
        cache: !1,
        success: function(n) {
            clearTimeout(timmer);
            n == "" ? $(".wrap-suggestion").remove() : f.length > 0 ? f.replaceWith(n) : e.append(n)
        }
    })
}
function UpDownSuggest(n) {
    var t = "ul.wrap-suggestion li", i;
    if (n == 40) {
        $(t + ".selected").length == 0 ? $(t + ":first").addClass("selected") : (i = $(t + ".selected").next(),
        $(t + ".selected").removeClass("selected"),
        i.addClass("selected"));
        return
    }
    if (n == 38) {
        $(t + ".selected").length == 0 ? $(t + ":last").addClass("selected") : (i = $(t + ".selected").prev(),
        $(t + ".selected").removeClass("selected"),
        i.addClass("selected"));
        return
    }
}
function getCookie(n) {
    for (var r, u, i = document.cookie.split(";"), t = 0; t < i.length; t++)
        if (r = i[t].substr(0, i[t].indexOf("=")),
        u = i[t].substr(i[t].indexOf("=") + 1),
        r = r.replace(/^\s+|\s+$/g, ""),
        r == n)
            return unescape(u)
}
function empty(n) {
    var t;
    if (n === "" || n === 0 || n === "0" || n === null || n === !1 || n === undefined || $.trim(n) == "")
        return !0;
    if (typeof n == "object") {
        for (t in n)
            if (typeof n[t] != "function")
                return !1;
        return !0
    }
    return !1
}
function getScriptChatTgdd() {
    // setTimeout(function() {
    //     var n, t;
    //     gl_fLoadChat || (gl_fLoadChat = !0,
    //     n = getCookie("chat.username"),
    //     (empty(n) || !empty(n) && n.indexOf("@") < 0) && ("undefined" != typeof g_version ? (t = "https://cdn.thegioididong.com/dmxchat/chatclienttgddmobile.v" + g_version + ".js",
    //     $.getScript(t).done(function() {
    //         console.log("GET SCRIPT CHAT: DONE")
    //     })) : (t = "https://cdn.thegioididong.com/dmxchat/chatclienttgddmobile.js",
    //     $.getScript(t).done(function() {
    //         console.log("GET SCRIPT CHAT: DONE")
    //     }))))
    // }, 1e4)
}
function CreateCookie(n, t, i) {
    var r = new Date, u;
    r.setDate(r.getDate() + i);
    u = escape(t) + (i == null ? "" : "; visited=true; path=/; domain=" + rooturl + "; expires=" + r.toUTCString() + ";");
    document.cookie = n + "=" + u
}
function CreateCookieWithHour(n, t, i) {
    var r = new Date, u;
    r.setMinutes(r.getMinutes() + i);
    u = escape(t) + (i == null ? "" : "; visited=true; path=/; domain=" + rooturl + "; expires=" + r.toUTCString() + ";");
    document.cookie = n + "=" + u
}
function Delete_Cookie(n, t, i) {
    getCookie(n) && (document.cookie = n + "=" + (t ? ";path=" + t : "") + (i ? ";domain=" + i : "") + ";expires=Thu, 01 Jan 1970 00:00:01 GMT")
}
function checkCmtParam() {
    setTimeout(function() {
        var n = getUrlParameter("cmtdt");
        n > 0 && $("#comment").length > 0 ? ($("html, body").animate({
            scrollTop: $("#comment").offset().top
        }, 500),
        setTimeout(function() {
            $(".wrap_seasort").addClass("hide");
            $(".cmtKey").val(n);
            var t = $.Event("keyup");
            t.keyCode = 13;
            $(".cmtKey").trigger(t);
            $(".cmtKey").val(n);
            setTimeout(function() {
                $(".s_comment").click();
                $(".cmtKey").val("");
                var n = window.location.origin + window.location.pathname
                  , t = "<a class='seeAllCmt' href='" + n + "'>Xem tất cả bình luận<\/a>";
                $(".wrap_comment .listcomment").after(t);
                $("#txtEditorExt").remove();
                $(".wrap_seasort").hide();
                $(".wrap_seasort").removeClass("hide")
            }, 1e3)
        }, 1e3)) : n == 0 && $("html, body").animate({
            scrollTop: $("#comment").offset().top
        }, 500)
    }, 2e3)
}
function getUrlParameter(n) {
    for (var u = decodeURIComponent(window.location.search.substring(1)), r = u.split("&"), t, i = 0; i < r.length; i++)
        if (t = r[i].split("="),
        t[0] === n)
            return t[1] === undefined ? !0 : t[1]
}
function getJsRateShip() {
    // setTimeout(function() {
    //     $.getScript("/Scripts/mobile/V4/ratingship.min.js").done(function() {
    //         console.log("getJsRateShip_js")
    //     })
    // }, 11e3)
}
function ganew_addImpression(n, t, i, r, u, f) {
    ga(domainTracking + "ec:addImpression", {
        id: n,
        name: t,
        category: i,
        brand: r,
        variant: u,
        price: f,
        position: 0
    })
}
function ganew_viewProduct(n, t, i, r, u, f, e) {
    ganew_addProduct(n, t, i, r, u, f, e);
    ganew_sendAction(eventAction.detail);
    ga(domainTracking + "send", "pageview")
}
function ganew_addCart(n, t, i, r, u, f, e) {
    ganew_addProduct(n, t, i, r, u, f, e);
    ganew_sendAction(eventAction.add);
    ganew_sendEvent(eventCategory, eventAction.click, "add to cart")
}
function ganew_updateCart(n, t) {
    var i = $.grep(cart, function(t) {
        return t.ProductId == n
    });
    ganew_addProductbyItem(i[0]);
    ganew_sendAction(eventAction.add);
    ga(domainTracking + "send", "event", eventCategory, eventAction.click, t)
}
function ganew_removeCart(n) {
    var t = $.grep(cart, function(t) {
        return t.ProductId == n
    });
    ganew_addProductbyItem(t[0]);
    ganew_sendAction(eventAction.remove);
    ganew_sendEvent(eventCategory, eventAction.click, "remove to cart")
}
function ganew_viewOrder() {
    ganew_stepCart(1, "checkout")
}
function ganew_stepCart(n, t) {
    checkout();
    ga(domainTracking + "ec:setAction", "checkout", {
        step: n,
        option: "step_" + t
    })
}
function ganew_completeOrder(n, t) {
    ga("set", "&cu", "VND");
    checkout();
    ga("ec:setAction", "purchase", {
        id: n,
        affiliation: "CartV4 - Online",
        revenue: t,
        coupon: ""
    });
    ganew_sendEvent(eventCategory, eventAction.detail, typeof cart != "undefined" && cart.length == 1 ? "complete order" : "complete order multi");
    ga("send", "pageview");
    typeof dataLayer != "undefined" && (productga.price = t,
    dataLayer.push({
        event: "addCartComplete"
    }))
}
function onShippingComplete(n, t) {
    ga(domainTracking + "ec:setAction", "checkout_option", {
        step: n,
        option: t
    });
    ga(domainTracking + "send", "event", "Checkout", "Option", {
        hitCallback: function() {}
    })
}
function checkout() {
    if (typeof cart != "undefined")
        for (var n = 0; n < cart.length; n++)
            ganew_addProductbyItem(cart[n])
}
function ganew_sendAction(n, t) {
    t != undefined ? ga(domainTracking + "ec:setAction", n, t) : ga(domainTracking + "ec:setAction", n)
}
function ganew_sendEvent(n, t, i) {
    // ga(domainTracking + "send", "event", n, t, i)
}
function ganew_addProduct(n, t, i, r, u, f, e) {
    ga(domainTracking + "ec:addProduct", {
        id: n,
        name: t,
        category: i,
        brand: r,
        variant: u,
        price: f,
        quantity: e
    })
}
function ganew_addProductbyItem(n) {
    ga(domainTracking + "ec:addProduct", {
        id: n.ProductId,
        name: n.ProductName,
        category: n.CategoryName,
        brand: n.BrandName,
        variant: n.ProductCode,
        price: n.ProductPrice,
        quantity: n.Quantity
    })
}
function ga_conversion(n) {
    if (typeof dataLayer != "undefined")
        switch (n) {
        case 1:
            dataLayer.push({
                event: "addCartCompleteAtHome"
            });
            break;
        case 2:
            dataLayer.push({
                event: "addCartCompleteAtStore"
            });
            break;
        case 3:
            dataLayer.push({
                event: "addCartCompleteAtInstallment"
            })
        }
}



function u_cart(){
    var form = $('form#order_sp')
    var formData = form.serialize();
    $.ajax({
        url: '/update-cart.html',
        type: form.attr("method"),
        cache: false,
        data: formData,
        success: function(data){
            $('#wrap_cart').parent().html(data.h)
        },
        error: function(){

        }
    })
}



function AddEventOrderAccessory() {    
    $(document).on('click',".listorder button.delete", function() {
        $(this).parent().parent().remove();
        u_cart();
    });
    
    $(".itemfull button.delete").click(function() {
        $(".itemfull").hide();
        POSTAjax("/aj/OrderV4/DeleteProductTmp", {}, BeforeSendAjax, function() {
            EndSendAjax()
        }, function() {
            EndSendAjax()
        }, !0)
    });



    $(document).on('click',".listorder li .choosenumber .augment", function() {
        //Tang
        var soluong = $(this).parents("li").find(".hdQuantity")
        var a = parseInt(soluong.val()) + 1;
        soluong.val(a) ;        
        u_cart();
    });
    $(document).on('click',".listorder li .choosenumber .abate", function() {
        //Giam
        var soluong = $(this).parents("li").find(".hdQuantity")
        var a = parseInt(soluong.val()) - 1;
        soluong.val(a) ;        
        u_cart();
    });



    $(".listorder .listcolor a").click(function() {
        var n = $(this).parents("li").attr("data-value");
        $(this).parents("li").find(".ProductCode").val($(this).attr("data-value"));
        $(this).parents("li").find(".ProductNameCode").val($(this).attr("data-name"));
        $(this).parents("li").find(".choosecolor span").text("Màu: " + $(this).attr("data-name"));
        $(this).parents("li").find(".colimg img").attr("src", $(this).find("img").attr("src"));
        cartConfig.colorclicksubmit && (POSTAjax("/aj/OrderV4/UpdateProductProductCode", {
            ProductId: n,
            ProductCode: $(this).attr("data-value")
        }, BeforeSendAjax, function(t) {
            UpdateInfoCart(t);
            LoadStore();
            EndSendAjax();
            ganew_updateCart(n, eventLabel.updateColor)
        }, function() {
            EndSendAjax()
        }, !0),
        LoadAllDistrictByProvince(),
        $("#BillingAddress_DistictId").val() != "-1" && setTimeout(function() {
            $(".area_address .listdist .scroll a[data-value=" + $("#BillingAddress_DistictId").val() + "]").click()
        }, 2e3))
    });
    $(".choosecolor").click(function() {
        $(this).find(".listcolor").slideToggle()
    });
    $("#IsShipAtStore").val() === "true" && ShowTabStore();
    $("#IsShipAtHome").val() === "true" && ShowTabHome();
    $(".promotion .title").click(function() {
        $(this).hide();
        $(this).parent(".promotion").find("span").css("display", "block")
    });
    $(".promotion .pro-chosse").click(function() {
        var n = $(this).parents("li").attr("data-value")
          , t = $(this).attr("data-group");
        $(".pro" + t).removeClass("choose");
        $(this).addClass("choose");
        POSTAjax("/aj/OrderV4/UpdatePromotion", {
            ProductId: n,
            ProductCode: $(this).attr("data-value"),
            GroupId: t,
            PromotionId: $(this).attr("data-promotionid"),
            IndexOrder: $(this).attr("data-index")
        }, BeforeSendAjax, function(t) {
            UpdateInfoCart(t);
            EndSendAjax();
            ganew_updateCart(n, eventLabel.updateColor)
        }, function() {
            EndSendAjax()
        }, !0)
    })
}
function UpdateInfoCart(n) {
    if ($(n).find(".detail_cart .listorder").length == 0)
        return window.location.href = "/gio-hang",
        !1;
    $(".detail_cart").html($(n).find(".detail_cart").html());
    $("header .cart span").html($(n).find("#TotalQuantityAll").val());
    AddEventOrderAccessory();
    $(n).filter("script").each(function() {
        $.globalEval(this.text || this.textContent || this.innerHTML || "")
    });
    $("#IsShipAtStore").val() === "true" && ($(".shipping_home").hide(),
    $(".shipping_store").show());
    InitDataTime()
}
function CartSubmitOrder() {
    return f_CartSubmitOrder ? !1 : (f_CartSubmitOrder = !0,
    $("#wrap_cart .massage").hide(),
    $("#IsShipAtStore").val() === "true" && ShowTabStore(),
    $("#IsShipAtHome").val() === "true" && ShowTabHome(),
    typeof localStorage != "undefined" && ($("#TrackTest").val(""),
    localStorage.getItem("sp-camp-c5") !== null && $("#TrackTest").val("sp-camp-c5"),
    localStorage.getItem("sp-camp-c3") !== null && $("#TrackTest").val($("#TrackTest").val() + "sp-camp-c3")),
    POSTAjax(cartConfig.linksubmitorder, $("#wrap_cart form").serialize(), BeforeSendAjax, function(n) {
        n.success === !0 ? window.location.href = n.data : $("#wrap_cart .message").show().html(n.errors);
        f_CartSubmitOrder = !1;
        EndSendAjax()
    }, function() {
        f_CartSubmitOrder = !1;
        var n = "";
        n += "<div><span onclick=\"$('.massage').hide();\">x<\/span>Hệ thông không ghi nhận được đơn hàng này.<br><div>Bạn vui lòng tải lại trang và thử lại<br><\/div><\/div>";
        $("#wrap_cart .massage").show().html(n);
        EndSendAjax()
    }, !0),
    !1)
}
function InitDataTime() {
    $("#BillingAddress_ProvinceId").val() == "3" || $("#BillingAddress_ProvinceId").val() == "5" ? ($(".showtimeship_address .listhours span.hcm:last").show(),
    $(".showtimeship_address .listhours span.ahour").show(),
    $("#IsStockHome").val() == "true" && $("#BillingAddress_ProvinceId").val() == "3" && $(".listorder li").length == 1 && $(".listorder li.samsung").length == 1 && $(".listorder li.samsung .hdQuantity").val() == "1" ? ($(".notemoreproduct").show(),
    $(".notemoreproduct .listmorecolor li").show().removeClass("choosed"),
    $("#IsNoteMoreProductCode").val(""),
    $('.notemoreproduct li[data-value="' + GetProductCode() + '"]').hide(),
    $(".notemoreproduct").hasClass("check") ? $("#IsNoteMoreColorProduct").val("true") : $("#IsNoteMoreColorProduct").val("false")) : ($(".notemoreproduct").hide(),
    $("#IsNoteMoreColorProduct").val("false"))) : ($(".showtimeship_address .listhours span.hcm:last").hide(),
    $(".showtimeship_address .listhours span:first").hide(),
    $(".notemoreproduct").hide(),
    $("#IsNoteMoreColorProduct").val("false"))
}
function ShowTabHome() {
    $("#IsShipAtHome").val("true");
    $("#IsShipAtStore").val("false");
    $("#IsShipAtHome").val() === "true" && ($(".area_address .OrderNoteHome").length > 0 && $("#OrderNote").val($(".area_address .OrderNoteHome").val()),
    $(this).toggleClass("check"),
    $(".cathe").hasClass("check") && $("#PaymentMethod").val(1),
    $(".shipping_home").show(),
    $(".shipping_store").hide(),
    $("#wrap_cart input[name='BillingAddress.ProvinceName']").val($("#wrap_cart .area_address .citydis .city span").text()),
    $("#wrap_cart input[name='BillingAddress.DistictName']").val($("#wrap_cart .area_address .citydis .dist span").text()),
    $("#BillingAddress_ProvinceId").val($("#wrap_cart .area_address .citydis .city span").attr("data-id")),
    $("#BillingAddress_DistictId").val($("#wrap_cart .area_address .citydis .dist span").attr("data-id")),
    typeof $(".area_address .date span").attr("data-id") != "undefined" && $("#BillingAddress_ShipDay").val($(".area_address .date span").attr("data-id")),
    $(".area_market").hide(),
    $(".area_address").show(),
    $(".area_market .EmailSecond").prop("disabled", !0),
    $(".area_address .EmailSecond").prop("disabled", !1))
}
function ChangeHours(n) {
    ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatHomeclickDay);
    var t = $(n).attr("data-value");
    $("#BillingAddress_ShipDay").val(t);
    $("#BillingAddress_ShipDay").val() !== "0" ? $(".today").css("display", "block") : $(".today").css("display", "none");
    InitDataTime();
    $(".area_address .date span").text($(n).text());
    $(".area_address .date span").attr("data-id", t);
    $(".listdate").hide();
    $(".listhours").show();
    $(".area_address .citydis .listhours span:visible").length > 0 && $(".area_address .citydis .listhours span:visible")[0].click()
}
function AddEventAtHome() {
    AddEventListProv();
    AddEventListDist();
    $(".area_address .listward .scroll a").click(function() {
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatHomeclickDistict);
        $("#BillingAddress_WardId-error").remove();
        $(".area_address .listward").hide();
        $(".area_address .ward span").attr("data-id", $(this).attr("data-value")).html($(this).text());
        $("#BillingAddress_WardId").val($(this).attr("data-value"));
        updateInfoShippingCost();
        updateInfoTmp()
    });
    $(".area_address .listmarket li").click(function() {
        $(this).hasClass("choosemarket") || ($(this).parent().find("li").removeClass("choosemarket"),
        $(this).addClass("choosemarket"),
        $(".area_address .listcity .scroll a[data-value=" + $(this).attr("data-pro") + "]").click(),
        $("#BillingAddress_DistictId").val($(this).attr("data-dis")),
        $("#BillingAddress_Address").val($(this).attr("data-ad")),
        setTimeout(function() {
            $(".area_address .listdist .scroll a[data-value=" + $("#BillingAddress_DistictId").val() + "]").click()
        }, 2e3),
        $(".area_address .citydis .dist span").html($(this).attr("data-disname")),
        $(".area_address .citydis .dist span").attr("data-id", $(this).attr("data-dis")),
        updateInfoTmp())
    });
    $(".area_address .date").click(function() {
        $(".layer").not(".listdate").hide();
        $(".listdate").slideToggle()
    });
    $(".area_address .hours").click(function() {
        $(".layer").not(".listhours").hide();
        $(".listhours").slideToggle()
    });
    $(".area_address .citydis .listhours span").click(function() {
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatHomeclickHour);
        $("#BillingAddress_ShipHour").val($(this).attr("data-value"));
        $(".area_address .hours span").text($(this).children(":visible").text());
        $(".layer").hide()
    });
    $(".notemoreproduct .first").click(function() {
        $(".notemoreproduct").toggleClass("check");
        $(".notemoreproduct").hasClass("check") ? ($("#IsNoteMoreColorProduct").val("true"),
        $(".notemoreproduct .last").show(),
        $(".notemoreproduct .first").hide()) : ($("#IsNoteMoreColorProduct").val("false"),
        $(".notemoreproduct .last").hide())
    });
    $(".notemoreproduct .listmorecolor li").click(function() {
        return $(this).hasClass("choosed") ? ($("#IsNoteMoreProductCode").val(""),
        $(".notemoreproduct .listmorecolor li").removeClass("choosed"),
        !1) : ($("#IsNoteMoreProductCode").val($(this).attr("data-value")),
        $(".notemoreproduct .listmorecolor li").removeClass("choosed"),
        $(this).addClass("choosed"),
        POSTAjax("/aj/OrderV4/CheckNoteMoreProduct", {
            iProvince: $("#BillingAddress_ProvinceId").val(),
            sProCode: $(this).attr("data-value"),
            iDistrict: $("#BillingAddress_DistictId").val()
        }, BeforeSendAjax, function(n) {
            n === "" && ($("#IsNoteMoreProductCode").val(""),
            $(".notemoreproduct .listmorecolor li").removeClass("choosed"),
            alert("Màu này đã hết hàng. Vui lòng chọn màu khác"));
            EndSendAjax()
        }, ErrorAjax, !0),
        !1)
    })
}
function AddEventListProv() {
    $(".area_address .citydis .listcity a").click(function() {
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatHomeclickProvince);
        $(".area_address .citydis .listcity").hide();
        $(".area_address .city span").attr("data-id", $(this).attr("data-value")).html($(this).text());
        $("#BillingAddress_ProvinceId").val($(this).attr("data-value"));
        $("#BillingAddress_DistictId").val("-1");
        $(".area_address .citydis .dist span").attr("data-id", "0").html("Chọn quận, huyện");
        $(".note_address").hide();
        LoadAllDistrictByProvince();
        updateInfoTmp();
        InitDataTime();
        $(".showtimeship_address .listdate span:first").click()
    })
}
function AddEventListDist() {
    $(".area_address .listdist .scroll a").click(function() {
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatHomeclickDistict);
        $("#BillingAddress_DistictId-error").remove();
        $(".area_address .listdist").hide();
        $(".area_address .dist span").attr("data-id", $(this).attr("data-value")).html($(this).text());
        $("#BillingAddress_DistictId").val($(this).attr("data-value"));
        var n = $(this).attr("data-stock");
        UpdateStockHome(n);
        LoadAllWardByDistrict();
        updateInfoTmp();
        $(".showtimeship_address .listdate span:first").click()
    })
}
function LoadAllProvinceAtHomeByProductCode() {
    // POSTAjax("/aj/OrderV4/LoadProviceByProductCode", {
    //     sProCode: GetProductCode()
    // }, BeforeSendAjax, function(n) {
    //     n != "" && $(".area_address .citydis .listcity .scroll").html(n);
    //     AddEventListProv();
    //     $(".area_address .listdist .scroll a[data-value=" + $("#BillingAddress_DistictId").val() + "]").click();
    //     EndSendAjax()
    // }, ErrorAjax, !0)
}
function UpdateStockHome(n) {
    n == "0" ? ($(".showtimeship_address").hide(),
    $("#IsStockHome").val("false"),
    $(".note_address").show(),
    $(".note_address p").html("Sản phẩm hiện đã <b>tạm hết hàng tại khu vực " + $(".area_address .citydis .dist span").html() + "<\/b>. Thời gian nhận hàng dự kiến sau <b>2 - 7 ngày<\/b>.")) : ($(".showtimeship_address").show(),
    $("#IsStockHome").val("true"),
    $(".note_address").hide(),
    $(".note_address p").html(""))
}
function LoadAllDistrictByProvince() {
    POSTAjax(url_LoadAllDistrictByProvince, {
        iProvince: $("#BillingAddress_ProvinceId").val(),
        sProCode: GetProductCode()
    }, function() {
        $(".area_address .overlay").show()
    }, function(n) {
        $(".area_address .listdist .scroll").html(n);
        AddEventListDist();
        LoadAllWardByDistrict();
        setTimeout(function() {
            $(".area_address .overlay").hide()
        }, 2e3)
    }, ErrorAjax, !0)
}
function LoadAllWardByDistrict() {
    $(".area_address .citydis .ward span").attr("data-id", "0").html("Chọn phường, xã");
    $("#BillingAddress_WardId").val("-1");
    POSTAjax(url_LoadAllWardByDistrict, {
        iDistrict: $("#BillingAddress_DistictId").val(),
        sProCode: GetProductCode()
    }, function() {
        $(".area_address .overlay").show()
    }, function(n) {
        $(".area_address .listward .scroll").html(n);
        $(".area_address .listward .scroll a").click(function() {
            ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatHomeclickDistict);
            $("#BillingAddress_WardId-error").remove();
            $(".area_address .listward").hide();
            $(".area_address .ward span").attr("data-id", $(this).attr("data-value")).html($(this).text());
            $("#BillingAddress_WardId").val($(this).attr("data-value"));
            updateInfoShippingCost();
            updateInfoTmp()
        });
        setTimeout(function() {
            $(".area_address .overlay").hide()
        }, 2e3)
    }, ErrorAjax, !0)
}
function updateInfoShippingCost() {
    $(".area_address .cod-area").html("");
    $(".area_address .overlay").show();
    updateInfoButtonShippingCost();
    $.ajax({
        async: !0,
        data: $("#wrap_cart form").serialize(),
        type: "POST",
        error: function() {
            $(".area_address .overlay").hide()
        },
        success: function(n) {
            $(".area_address .cod-area").html($(n).html());
            updateInfoButtonShippingCost();
            $(".area_address .cod-area label").click(function() {
                $(".area_address .cod-area label").removeClass("choosed");
                $(".area_address .cod-area p").hide();
                $(this).addClass("choosed");
                $(this).next().show();
                $(this).attr("data-cod") == "0" ? $("#IsShipFast").val("false") : $("#IsShipFast").val("true");
                updateInfoButtonShippingCost()
            });
            $(".area_address .overlay").hide()
        },
        url: "/aj/OrderV4/UpdateShippingCost"
    })
}
function updateInfoButtonShippingCost() {
    $(".area_address .new-cod label.choosed").length == 1 ? ($(".area_address .new-cod label.choosed").attr("data-sms") == "1" ? $(".payoffline").html("Hoàn tất<span>Nhắn tin SMS xác nhận " + $(".area_address .new-cod label.choosed").attr("data-cost") + "<\/span>") : $(".area_address .city span").attr("data-id") != "3" && $(".area_address .new-cod label.choosed").attr("data-cost") != "0" ? $(".payoffline").html("Thanh toán tại siêu thị<span>Tối thiểu " + $(".area_address .new-cod label.choosed").attr("data-cost") + "<\/span>") : $(".payoffline").html("Thanh toán khi nhận hàng<span>Xem hàng trước, không mua không sao<\/span>"),
    $(".payonline span:first").text("Tổng tiền đơn hàng " + $(".area_address .new-cod label.choosed").attr("data-total"))) : $(".payoffline").html("Thanh toán khi nhận hàng<span>Xem hàng trước, không mua không sao<\/span>")
}
function ShowTabStore() {
    $("#IsShipAtHome").val("false");
    $("#IsShipAtStore").val("true");
    $("#IsShipAtStore").val() === "true" && ($("#wrap_cart input[name='BillingAddress.ProvinceName']").val($("#wrap_cart .area_market .citydis .city span").text()),
    $("#wrap_cart input[name='BillingAddress.DistictName']").val($("#wrap_cart .area_market .citydis .dist span").text()),
    $("#BillingAddress_ProvinceId").val($("#wrap_cart .area_market .citydis .city span").attr("data-id")),
    $("#BillingAddress_DistictId").val($("#wrap_cart .area_market .citydis .dist span").attr("data-id")),
    typeof $(".area_market .date span").attr("data-id") != "undefined" && $("#BillingAddress_ShipDay").val($(".area_market .date span").attr("data-id")),
    $(".area_market .OrderNoteHome").length > 0 && $("#OrderNote").val($(".area_market .homenumber").val()),
    $("#PaymentMethod").val() == "1" && $("#PaymentMethod").val(0),
    $(".shipping_home").hide(),
    $(".shipping_store").show(),
    $(".area_market").show(),
    $(".area_address").hide(),
    $(".area_market .EmailSecond").prop("disabled", !1),
    $(".area_address .EmailSecond").prop("disabled", !0))
}
function EventDistClick() {
    $(".area_market .citydis .listdist .scroll a").click(function() {
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatStoreclickDistict);
        $(".area_market .listdist").hide();
        $(".area_market .citydis .dist span").attr("data-id", $(this).attr("data-value")).html($(this).text());
        $("#BillingAddress_DistictId").val($(this).attr("data-value"));
        LoadStore()
    })
}
function AddEventAtStore() {
    $(".area_market .citydis .listcity a").click(function() {
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatStoreclickProvince);
        $(".area_market .listcity").hide();
        $(".area_market .city span").attr("data-id", $(this).attr("data-value")).html($(this).text());
        $("#BillingAddress_ProvinceId").val($(this).attr("data-value"));
        $("#BillingAddress_DistictId").val("-1");
        $(".area_market .dist span").attr("data-id", "0").html("Chọn quận, huyện");
        POSTAjax(cartConfig.link_store_loaddistict, {
            iProvince: $("#BillingAddress_ProvinceId").val(),
            sProCode: GetProductCode(),
            iProductID: $("#ProductId").val()
        }, function() {
            $(".area_market .overlay").show()
        }, function(n) {
            $(".area_market .listdist .scroll").html(n);
            EventDistClick();
            LoadStore()
        }, ErrorAjax, !0)
    });
    EventDistClick();
    $("#BillingAddress_ProvinceId").val() != "0" && $("#buyatstore").length == 1 && LoadAllDistrictStoreByProvince();
    $(".area_market .choosetime .liday .city a:first").click();
    $(".area_market .date").click(function() {
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatStoreclickDay);
        $(".listdate").slideToggle()
    });
    $(".area_market .listdate span").click(function() {
        $(".listdate").hide();
        $(".area_market .date span").text($(this).text());
        $("#BillingAddress_ShipDay").val($(this).attr("data-value"));
        $(".area_market .date span").attr("data-id", $(this).attr("data-value"))
    })
}
function LoadAllDistrictStoreByProvince() {
    POSTAjax(cartConfig.link_store_loaddistict, {
        iProvince: $("#BillingAddress_ProvinceId").val(),
        sProCode: GetProductCode(),
        iProductID: $("#ProductId").val()
    }, BeforeSendAjax, function(n) {
        $(".area_market .listdist .listcity").html(n);
        EventDistClick();
        $(".area_market .citydis .listdist .scroll a:first").click();
        EndSendAjax()
    }, ErrorAjax, !0)
}
function LoadAllProvince() {
    POSTAjax(cartConfig.link_store_loadprovince, {
        sProCode: GetProductCode(),
        iProductID: $("#ProductId").val()
    }, function() {
        $(".area_market .overlay").show()
    }, function(n) {
        $(".area_market .listcity .scroll").html(n);
        $(".area_market .overlay").hide();
        $(".area_market .citydis .listcity a").click(function() {
            ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatStoreclickProvince);
            $(".area_market .listcity").hide();
            $(".area_market .city span").attr("data-id", $(this).attr("data-value")).html($(this).text());
            $("#BillingAddress_ProvinceId").val($(this).attr("data-value"));
            $("#BillingAddress_DistictId").val("-1");
            $(".area_market .dist span").attr("data-id", "0").html("Chọn quận, huyện");
            POSTAjax(cartConfig.link_store_loaddistict, {
                iProvince: $("#BillingAddress_ProvinceId").val(),
                sProCode: GetProductCode(),
                iProductID: $("#ProductId").val()
            }, function() {
                $(".area_market .overlay").show()
            }, function(n) {
                $(".area_market .listdist .scroll").html(n);
                EventDistClick();
                LoadStore()
            }, ErrorAjax, !0)
        });
        $(".area_market .citydis .listcity a:first").click()
    }, ErrorAjax, !0)
}
function LoadStore() {
    $(".area_market  .listmarket").html("");
    POSTAjax(cartConfig.link_loadstore, {
        iProvince: $("#BillingAddress_ProvinceId").val(),
        iDistrict: $("#BillingAddress_DistictId").val(),
        sProCode: GetProductCode()
    }, function() {
        $(".area_market .overlay").show()
    }, function(n) {
        $(".area_market  .listmarket").html(n);
        $("#BillingAddress_StoreId").val("0");
        $("#BillingAddress_StoreName").val("");
        EventStoreClickItem();
        $(".listmarket .viewmorest").click(function() {
            ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatStoreclickStoreMore);
            $(this).hide();
            $(this).next().removeClass("none");
            $(this).next().next().removeClass("none");
            $(this).next().next().next().removeClass("none");
            $(this).next().next().next().next().removeClass("none");
            $(this).next().next().next().next().next().removeClass("none");
            $(this).next().next().next().next().next().next().removeClass("none")
        });
        $(".area_market .overlay").hide();
        $(".searchstore input[type=text]").val("").trigger("keyup")
    }, ErrorAjax, !0)
}
function EventStoreClickItem() {
    $(".area_market .cod-area").html("");
    $(".area_market .listmarket li").click(function() {
        (ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatStoreclickStore),
        $("#BillingAddress_StoreId").val($(this).attr("data-id")),
        $("#BillingAddress_StoreName").val($(this).find(".name").text()),
        $("#BillingAddress_StoreId-error").remove(),
        $(this).hasClass("choosemarket")) || ($(this).parent().find("li").removeClass("choosemarket"),
        $(this).addClass("choosemarket"),
        UpdateStockStore($(this).attr("data-stock")))
    })
}
function updateInfoShippingCostStore() {
    $(".area_market .cod-area").html("");
    $(".area_market .overlay").show();
    updateInfoButtonShippingCostStore();
    $.ajax({
        async: !0,
        data: $("#wrap_cart form").serialize(),
        type: "POST",
        error: function() {
            $(".area_market .overlay").hide()
        },
        success: function(n) {
            $(".area_market .cod-area").html($(n).html());
            updateInfoButtonShippingCostStore();
            $(".area_market .cod-area label").click(function() {
                $(".area_market .cod-area label").removeClass("choosed");
                $(".area_market .cod-area p").hide();
                $(this).addClass("choosed");
                $(this).next().show();
                $(this).attr("data-cod") == "0" ? $("#IsShipFast").val("false") : $("#IsShipFast").val("true");
                updateInfoButtonShippingCostStore()
            });
            $(".area_market .overlay").hide()
        },
        url: "/aj/OrderV4/UpdateShippingCost"
    })
}
function updateInfoButtonShippingCostStore() {
    $(".area_market .new-cod label.choosed").length == 1 ? ($(".area_market .new-cod label.choosed").attr("data-sms") == "1" ? $(".payoffline").html("Hoàn tất<span>Nhắn tin SMS xác nhận " + $(".area_market .new-cod label.choosed").attr("data-cost") + "<\/span>") : $(".area_market .city span").attr("data-id") != "3" && $(".payoffline").html("Thanh toán tại siêu thị<span>Tối thiểu " + $(".area_market .new-cod label.choosed").attr("data-cost") + "<\/span>"),
    $(".payonline span:first").text("Tổng tiền đơn hàng " + $(".area_market .new-cod label.choosed").attr("data-total"))) : $(".payoffline").html("Thanh toán khi nhận hàng<span>Xem hàng trước, không mua không sao<\/span>")
}
function UpdateStockStore(n) {
    n == "1" ? ($(".area_market .timeship").show(),
    $(".area_market .note_market").hide(),
    $("#IsStock").val("true")) : ($(".area_market .timeship").hide(),
    $(".area_market .note_market").show(),
    $("#IsStock").val("false"))
}
function StoreActiveTabStore(n, t, i) {
    $(".supermarket label").click();
    $('.area_market .citydis .listcity a[data-value="' + t + '"]').click();
    CheckClidkDist(i, n)
}
function CheckClidkDist(n, t) {
    $('.area_market .citydis .listdist .scroll a[data-value="' + n + '"]').length == 0 && n > 0 ? setTimeout(function() {
        CheckClidkDist(n, t)
    }, 200) : $(".area_market  .listmarket li").length == 0 ? setTimeout(function() {
        CheckClidkDist(n, t)
    }, 300) : ($('.area_market .citydis .listdist .scroll a[data-value="' + n + '"]').click(),
    setTimeout(function() {
        CheckClickStore(t)
    }, 1e3))
}
function CheckClickStore(n) {
    if ($('.area_market .listmarket li[data-id="' + n + '"]').length == 0)
        setTimeout(function() {
            CheckClickStore(n)
        }, 200);
    else {
        var t = $(".area_market .listmarket li:first")
          , i = $(".area_market .listmarket li:first").attr("data-id")
          , r = $('.area_market .listmarket li[data-id="' + n + '"]');
        $('.area_market .listmarket li[data-id="' + n + '"]').replaceWith(t);
        $(".area_market .listmarket").prepend(r);
        $(".area_market .listmarket li:first").hasClass("none") && ($(".area_market .listmarket li:first").removeClass("none"),
        $('.area_market .listmarket li[data-id="' + i + '"]').addClass("none"));
        EventStoreClickItem();
        $('.area_market .listmarket li[data-id="' + n + '"]').click()
    }
}
function addEventPromotionAcc() {
    $(".buy_promotion .buyacc").click(function() {
        var n = $(this).attr("data-href");
        return $(this).addClass("none"),
        $(this).next().removeClass("none"),
        POSTAjax(n, {}, BeforeSendAjax, function(n) {
            UpdateInfoCart(n);
            EndSendAjax()
        }, ErrorAjax, !0),
        !1
    })
}
function updatePromotionAcc() {
    POSTAjax("/aj/OrderV4/CheckAccessoriesPromotion", {}, BeforeSendAjax, function(n) {
        $(".buy_promotion").replaceWith(n);
        addEventPromotionAcc();
        EndSendAjax()
    }, ErrorAjax, !0)
}
function locdau(n) {
    return n = n.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a"),
    n = n.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e"),
    n = n.replace(/ì|í|ị|ỉ|ĩ/g, "i"),
    n = n.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o"),
    n = n.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u"),
    n = n.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y"),
    n = n.replace(/đ/g, "d"),
    n = n.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A"),
    n = n.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E"),
    n = n.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I"),
    n = n.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O"),
    n = n.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U"),
    n = n.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y"),
    n.replace(/Đ/g, "D")
}
function GetProductCode() {
    var n = "";
    return $(".ProductCode").each(function() {
        n += $(this).val() + ","
    }),
    n = n.trimRight(",")
}
function BeforeSendAjax() {
    $(".loading-cart").show();
    $("body").addClass("htmlloading");
    $("html").addClass("htmlloading")
}
function EndSendAjax() {
    $(".loading-cart").hide();
    $("body").removeClass("htmlloading");
    $("html").removeClass("htmlloading")
}
function ErrorAjax() {
    $(".loading-cart").hide();
    $("body").removeClass("htmlloading");
    $("html").removeClass("htmlloading")
}
function CheckOrderValidateNew() {
    $("#IsShipAtStore").val() != "true" ? ($("#BillingAddress_StoreId").removeClass("min1"),
    $(".area_market .EmailSecond").prop("disabled", !0)) : ($("#BillingAddress_StoreId").addClass("min1"),
    $(".area_market .EmailSecond").prop("disabled", !1));
    $("#IsShipAtHome").val() != "true" ? ($("#BillingAddress_DistictId").removeClass("min1"),
    $(".area_address .EmailSecond").prop("disabled", !0)) : ($("#BillingAddress_DistictId").addClass("min1"),
    $(".area_address .EmailSecond").prop("disabled", !1));
    form.valid() && CartSubmitOrder()
}
var lastSuggest, timmer, gl_fLoadChat, cartConfig, f_CartSubmitOrder, url_LoadAllDistrictByProvince, url_LoadAllWardByDistrict, flag_firstloadstore, updateInfoTmp, form;
(function(n, t) {
    function yu(n) {
        var t = wt[n] = {};
        return i.each(n.split(h), function(n, i) {
            t[i] = !0
        }),
        t
    }
    function ui(n, r, u) {
        if (u === t && n.nodeType === 1) {
            var f = "data-" + r.replace(sr, "-$1").toLowerCase();
            if (u = n.getAttribute(f),
            typeof u == "string") {
                try {
                    u = u === "true" ? !0 : u === "false" ? !1 : u === "null" ? null : +u + "" === u ? +u : or.test(u) ? i.parseJSON(u) : u
                } catch (e) {}
                i.data(n, r, u)
            } else
                u = t
        }
        return u
    }
    function at(n) {
        for (var t in n)
            if ((t !== "data" || !i.isEmptyObject(n[t])) && t !== "toJSON")
                return !1;
        return !0
    }
    function v() {
        return !1
    }
    function g() {
        return !0
    }
    function k(n) {
        return !n || !n.parentNode || n.parentNode.nodeType === 11
    }
    function fi(n, t) {
        do
            n = n[t];
        while (n && n.nodeType !== 1);return n
    }
    function ei(n, t, r) {
        if (t = t || 0,
        i.isFunction(t))
            return i.grep(n, function(n, i) {
                var u = !!t.call(n, i, n);
                return u === r
            });
        if (t.nodeType)
            return i.grep(n, function(n) {
                return n === t === r
            });
        if (typeof t == "string") {
            var u = i.grep(n, function(n) {
                return n.nodeType === 1
            });
            if (fe.test(t))
                return i.filter(t, u, !r);
            t = i.filter(t, u)
        }
        return i.grep(n, function(n) {
            return i.inArray(n, t) >= 0 === r
        })
    }
    function oi(n) {
        var i = kr.split("|")
          , t = n.createDocumentFragment();
        if (t.createElement)
            while (i.length)
                t.createElement(i.pop());
        return t
    }
    function pu(n, t) {
        return n.getElementsByTagName(t)[0] || n.appendChild(n.ownerDocument.createElement(t))
    }
    function si(n, t) {
        if (t.nodeType === 1 && i.hasData(n)) {
            var u, f, o, s = i._data(n), r = i._data(t, s), e = s.events;
            if (e) {
                delete r.handle;
                r.events = {};
                for (u in e)
                    for (f = 0,
                    o = e[u].length; f < o; f++)
                        i.event.add(t, u, e[u][f])
            }
            r.data && (r.data = i.extend({}, r.data))
        }
    }
    function hi(n, t) {
        var r;
        t.nodeType === 1 && (t.clearAttributes && t.clearAttributes(),
        t.mergeAttributes && t.mergeAttributes(n),
        r = t.nodeName.toLowerCase(),
        r === "object" ? (t.parentNode && (t.outerHTML = n.outerHTML),
        i.support.html5Clone && n.innerHTML && !i.trim(t.innerHTML) && (t.innerHTML = n.innerHTML)) : r === "input" && nu.test(n.type) ? (t.defaultChecked = t.checked = n.checked,
        t.value !== n.value && (t.value = n.value)) : r === "option" ? t.selected = n.defaultSelected : r === "input" || r === "textarea" ? t.defaultValue = n.defaultValue : r === "script" && t.text !== n.text && (t.text = n.text),
        t.removeAttribute(i.expando))
    }
    function nt(n) {
        return typeof n.getElementsByTagName != "undefined" ? n.getElementsByTagName("*") : typeof n.querySelectorAll != "undefined" ? n.querySelectorAll("*") : []
    }
    function ci(n) {
        nu.test(n.type) && (n.defaultChecked = n.checked)
    }
    function li(n, t) {
        if (t in n)
            return t;
        for (var r = t.charAt(0).toUpperCase() + t.slice(1), u = t, i = fu.length; i--; )
            if (t = fu[i] + r,
            t in n)
                return t;
        return u
    }
    function tt(n, t) {
        return n = t || n,
        i.css(n, "display") === "none" || !i.contains(n.ownerDocument, n)
    }
    function ai(n, t) {
        for (var r, o, e = [], f = 0, s = n.length; f < s; f++)
            (r = n[f],
            r.style) && (e[f] = i._data(r, "olddisplay"),
            t ? (!e[f] && r.style.display === "none" && (r.style.display = ""),
            r.style.display === "" && tt(r) && (e[f] = i._data(r, "olddisplay", wi(r.nodeName)))) : (o = u(r, "display"),
            !e[f] && o !== "none" && i._data(r, "olddisplay", o)));
        for (f = 0; f < s; f++)
            (r = n[f],
            r.style) && (t && r.style.display !== "none" && r.style.display !== "" || (r.style.display = t ? e[f] || "" : "none"));
        return n
    }
    function vi(n, t, i) {
        var r = be.exec(t);
        return r ? Math.max(0, r[1] - (i || 0)) + (r[2] || "px") : t
    }
    function yi(n, t, r, f) {
        for (var e = r === (f ? "border" : "content") ? 4 : t === "width" ? 1 : 0, o = 0; e < 4; e += 2)
            r === "margin" && (o += i.css(n, r + c[e], !0)),
            f ? (r === "content" && (o -= parseFloat(u(n, "padding" + c[e])) || 0),
            r !== "margin" && (o -= parseFloat(u(n, "border" + c[e] + "Width")) || 0)) : (o += parseFloat(u(n, "padding" + c[e])) || 0,
            r !== "padding" && (o += parseFloat(u(n, "border" + c[e] + "Width")) || 0));
        return o
    }
    function pi(n, t, r) {
        var f = t === "width" ? n.offsetWidth : n.offsetHeight
          , e = !0
          , o = i.support.boxSizing && i.css(n, "boxSizing") === "border-box";
        if (f <= 0 || f == null) {
            if (f = u(n, t),
            (f < 0 || f == null) && (f = n.style[t]),
            ot.test(f))
                return f;
            e = o && (i.support.boxSizingReliable || f === n.style[t]);
            f = parseFloat(f) || 0
        }
        return f + yi(n, t, r || (o ? "border" : "content"), e) + "px"
    }
    function wi(n) {
        if (ti[n])
            return ti[n];
        var f = i("<" + n + ">").appendTo(r.body)
          , t = f.css("display");
        return f.remove(),
        (t === "none" || t === "") && (y = r.body.appendChild(y || i.extend(r.createElement("iframe"), {
            frameBorder: 0,
            width: 0,
            height: 0
        })),
        p && y.createElement || (p = (y.contentWindow || y.contentDocument).document,
        p.write("<!doctype html><html><body>"),
        p.close()),
        f = p.body.appendChild(p.createElement(n)),
        t = u(f, "display"),
        r.body.removeChild(y)),
        ti[n] = t,
        t
    }
    function vt(n, t, r, u) {
        var f;
        if (i.isArray(t))
            i.each(t, function(t, i) {
                r || to.test(n) ? u(n, i) : vt(n + "[" + (typeof i == "object" ? t : "") + "]", i, r, u)
            });
        else if (r || i.type(t) !== "object")
            u(n, t);
        else
            for (f in t)
                vt(n + "[" + f + "]", t[f], r, u)
    }
    function bi(n) {
        return function(t, r) {
            typeof t != "string" && (r = t,
            t = "*");
            var u, o, f, s = t.toLowerCase().split(h), e = 0, c = s.length;
            if (i.isFunction(r))
                for (; e < c; e++)
                    u = s[e],
                    f = /^\+/.test(u),
                    f && (u = u.substr(1) || "*"),
                    o = n[u] = n[u] || [],
                    o[f ? "unshift" : "push"](r)
        }
    }
    function it(n, i, r, u, f, e) {
        f = f || i.dataTypes[0];
        e = e || {};
        e[f] = !0;
        for (var o, s = n[f], h = 0, l = s ? s.length : 0, c = n === ii; h < l && (c || !o); h++)
            o = s[h](i, r, u),
            typeof o == "string" && (!c || e[o] ? o = t : (i.dataTypes.unshift(o),
            o = it(n, i, r, u, o, e)));
        return (c || !o) && !e["*"] && (o = it(n, i, r, u, "*", e)),
        o
    }
    function ki(n, r) {
        var u, f, e = i.ajaxSettings.flatOptions || {};
        for (u in r)
            r[u] !== t && ((e[u] ? n : f || (f = {}))[u] = r[u]);
        f && i.extend(!0, n, f)
    }
    function wu(n, i, r) {
        var o, u, e, s, h = n.contents, f = n.dataTypes, c = n.responseFields;
        for (u in c)
            u in r && (i[c[u]] = r[u]);
        while (f[0] === "*")
            f.shift(),
            o === t && (o = n.mimeType || i.getResponseHeader("content-type"));
        if (o)
            for (u in h)
                if (h[u] && h[u].test(o)) {
                    f.unshift(u);
                    break
                }
        if (f[0]in r)
            e = f[0];
        else {
            for (u in r) {
                if (!f[0] || n.converters[u + " " + f[0]]) {
                    e = u;
                    break
                }
                s || (s = u)
            }
            e = e || s
        }
        if (e)
            return e !== f[0] && f.unshift(e),
            r[e]
    }
    function bu(n, t) {
        var i, o, r, e, s = n.dataTypes.slice(), f = s[0], u = {}, h = 0;
        if (n.dataFilter && (t = n.dataFilter(t, n.dataType)),
        s[1])
            for (i in n.converters)
                u[i.toLowerCase()] = n.converters[i];
        for (; r = s[++h]; )
            if (r !== "*") {
                if (f !== "*" && f !== r) {
                    if (i = u[f + " " + r] || u["* " + r],
                    !i)
                        for (o in u)
                            if (e = o.split(" "),
                            e[1] === r && (i = u[f + " " + e[0]] || u["* " + e[0]],
                            i)) {
                                i === !0 ? i = u[o] : u[o] !== !0 && (r = e[0],
                                s.splice(h--, 0, r));
                                break
                            }
                    if (i !== !0)
                        if (i && n.throws)
                            t = i(t);
                        else
                            try {
                                t = i(t)
                            } catch (c) {
                                return {
                                    state: "parsererror",
                                    error: i ? c : "No conversion from " + f + " to " + r
                                }
                            }
                }
                f = r
            }
        return {
            state: "success",
            data: t
        }
    }
    function di() {
        try {
            return new n.XMLHttpRequest
        } catch (t) {}
    }
    function ku() {
        try {
            return new n.ActiveXObject("Microsoft.XMLHTTP")
        } catch (t) {}
    }
    function gi() {
        return setTimeout(function() {
            b = t
        }, 0),
        b = i.now()
    }
    function du(n, t) {
        i.each(t, function(t, i) {
            for (var u = (d[t] || []).concat(d["*"]), r = 0, f = u.length; r < f; r++)
                if (u[r].call(n, t, i))
                    return
        })
    }
    function nr(n, t, r) {
        var e, o = 0, c = lt.length, f = i.Deferred().always(function() {
            delete h.elem
        }), h = function() {
            for (var o = b || gi(), t = Math.max(0, u.startTime + u.duration - o), s = t / u.duration || 0, i = 1 - s, r = 0, e = u.tweens.length; r < e; r++)
                u.tweens[r].run(i);
            return f.notifyWith(n, [u, i, t]),
            i < 1 && e ? t : (f.resolveWith(n, [u]),
            !1)
        }, u = f.promise({
            elem: n,
            props: i.extend({}, t),
            opts: i.extend(!0, {
                specialEasing: {}
            }, r),
            originalProperties: t,
            originalOptions: r,
            startTime: b || gi(),
            duration: r.duration,
            tweens: [],
            createTween: function(t, r) {
                var f = i.Tween(n, u.opts, t, r, u.opts.specialEasing[t] || u.opts.easing);
                return u.tweens.push(f),
                f
            },
            stop: function(t) {
                for (var i = 0, r = t ? u.tweens.length : 0; i < r; i++)
                    u.tweens[i].run(1);
                return t ? f.resolveWith(n, [u, t]) : f.rejectWith(n, [u, t]),
                this
            }
        }), s = u.props;
        for (gu(s, u.opts.specialEasing); o < c; o++)
            if (e = lt[o].call(u, n, s, u.opts),
            e)
                return e;
        return du(u, s),
        i.isFunction(u.opts.start) && u.opts.start.call(n, u),
        i.fx.timer(i.extend(h, {
            anim: u,
            queue: u.opts.queue,
            elem: n
        })),
        u.progress(u.opts.progress).done(u.opts.done, u.opts.complete).fail(u.opts.fail).always(u.opts.always)
    }
    function gu(n, t) {
        var r, f, e, u, o;
        for (r in n)
            if (f = i.camelCase(r),
            e = t[f],
            u = n[r],
            i.isArray(u) && (e = u[1],
            u = n[r] = u[0]),
            r !== f && (n[f] = u,
            delete n[r]),
            o = i.cssHooks[f],
            o && "expand"in o) {
                u = o.expand(u);
                delete n[f];
                for (r in u)
                    r in n || (n[r] = u[r],
                    t[r] = e)
            } else
                t[f] = e
    }
    function nf(n, t, r) {
        var o, u, a, v, s, y, l, f, b, h = this, e = n.style, p = {}, w = [], c = n.nodeType && tt(n);
        r.queue || (f = i._queueHooks(n, "fx"),
        f.unqueued == null && (f.unqueued = 0,
        b = f.empty.fire,
        f.empty.fire = function() {
            f.unqueued || b()
        }
        ),
        f.unqueued++,
        h.always(function() {
            h.always(function() {
                f.unqueued--;
                i.queue(n, "fx").length || f.empty.fire()
            })
        }));
        n.nodeType === 1 && ("height"in t || "width"in t) && (r.overflow = [e.overflow, e.overflowX, e.overflowY],
        i.css(n, "display") === "inline" && i.css(n, "float") === "none" && (!i.support.inlineBlockNeedsLayout || wi(n.nodeName) === "inline" ? e.display = "inline-block" : e.zoom = 1));
        r.overflow && (e.overflow = "hidden",
        i.support.shrinkWrapBlocks || h.done(function() {
            e.overflow = r.overflow[0];
            e.overflowX = r.overflow[1];
            e.overflowY = r.overflow[2]
        }));
        for (o in t)
            if (a = t[o],
            ao.exec(a)) {
                if (delete t[o],
                y = y || a === "toggle",
                a === (c ? "hide" : "show"))
                    continue;
                w.push(o)
            }
        if (v = w.length,
        v)
            for (s = i._data(n, "fxshow") || i._data(n, "fxshow", {}),
            ("hidden"in s) && (c = s.hidden),
            y && (s.hidden = !c),
            c ? i(n).show() : h.done(function() {
                i(n).hide()
            }),
            h.done(function() {
                var t;
                i.removeData(n, "fxshow", !0);
                for (t in p)
                    i.style(n, t, p[t])
            }),
            o = 0; o < v; o++)
                u = w[o],
                l = h.createTween(u, c ? s[u] : 0),
                p[u] = s[u] || i.style(n, u),
                u in s || (s[u] = l.start,
                c && (l.end = l.start,
                l.start = u === "width" || u === "height" ? 1 : 0))
    }
    function f(n, t, i, r, u) {
        return new f.prototype.init(n,t,i,r,u)
    }
    function rt(n, t) {
        var r, i = {
            height: n
        }, u = 0;
        for (t = t ? 1 : 0; u < 4; u += 2 - t)
            r = c[u],
            i["margin" + r] = i["padding" + r] = n;
        return t && (i.opacity = i.width = n),
        i
    }
    function tr(n) {
        return i.isWindow(n) ? n : n.nodeType === 9 ? n.defaultView || n.parentWindow : !1
    }
    var ir, ut, r = n.document, tf = n.location, rf = n.navigator, uf = n.jQuery, ff = n.$, rr = Array.prototype.push, o = Array.prototype.slice, ur = Array.prototype.indexOf, ef = Object.prototype.toString, yt = Object.prototype.hasOwnProperty, pt = String.prototype.trim, i = function(n, t) {
        return new i.fn.init(n,t,ir)
    }, ft = /[\-+]?(?:\d*\.|)\d+(?:[eE][\-+]?\d+|)/.source, of = /\S/, h = /\s+/, sf = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, hf = /^(?:[^#<]*(<[\w\W]+>)[^>]*$|#([\w\-]*)$)/, fr = /^<(\w+)\s*\/?>(?:<\/\1>|)$/, cf = /^[\],:{}\s]*$/, lf = /(?:^|:|,)(?:\s*\[)+/g, af = /\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g, vf = /"[^"\\\r\n]*"|true|false|null|-?(?:\d\d*\.|)\d+(?:[eE][\-+]?\d+|)/g, yf = /^-ms-/, pf = /-([\da-z])/gi, wf = function(n, t) {
        return (t + "").toUpperCase()
    }, et = function() {
        r.addEventListener ? (r.removeEventListener("DOMContentLoaded", et, !1),
        i.ready()) : r.readyState === "complete" && (r.detachEvent("onreadystatechange", et),
        i.ready())
    }, er = {}, wt, or, sr, w, ht, vu, ri;
    i.fn = i.prototype = {
        constructor: i,
        init: function(n, u, f) {
            var e, o, s;
            if (!n)
                return this;
            if (n.nodeType)
                return this.context = this[0] = n,
                this.length = 1,
                this;
            if (typeof n == "string") {
                if (e = n.charAt(0) === "<" && n.charAt(n.length - 1) === ">" && n.length >= 3 ? [null, n, null] : hf.exec(n),
                e && (e[1] || !u)) {
                    if (e[1])
                        return u = u instanceof i ? u[0] : u,
                        s = u && u.nodeType ? u.ownerDocument || u : r,
                        n = i.parseHTML(e[1], s, !0),
                        fr.test(e[1]) && i.isPlainObject(u) && this.attr.call(n, u, !0),
                        i.merge(this, n);
                    if (o = r.getElementById(e[2]),
                    o && o.parentNode) {
                        if (o.id !== e[2])
                            return f.find(n);
                        this.length = 1;
                        this[0] = o
                    }
                    return this.context = r,
                    this.selector = n,
                    this
                }
                return !u || u.jquery ? (u || f).find(n) : this.constructor(u).find(n)
            }
            return i.isFunction(n) ? f.ready(n) : (n.selector !== t && (this.selector = n.selector,
            this.context = n.context),
            i.makeArray(n, this))
        },
        selector: "",
        jquery: "1.8.3",
        length: 0,
        size: function() {
            return this.length
        },
        toArray: function() {
            return o.call(this)
        },
        get: function(n) {
            return n == null ? this.toArray() : n < 0 ? this[this.length + n] : this[n]
        },
        pushStack: function(n, t, r) {
            var u = i.merge(this.constructor(), n);
            return u.prevObject = this,
            u.context = this.context,
            t === "find" ? u.selector = this.selector + (this.selector ? " " : "") + r : t && (u.selector = this.selector + "." + t + "(" + r + ")"),
            u
        },
        each: function(n, t) {
            return i.each(this, n, t)
        },
        ready: function(n) {
            return i.ready.promise().done(n),
            this
        },
        eq: function(n) {
            return n = +n,
            n === -1 ? this.slice(n) : this.slice(n, n + 1)
        },
        first: function() {
            return this.eq(0)
        },
        last: function() {
            return this.eq(-1)
        },
        slice: function() {
            return this.pushStack(o.apply(this, arguments), "slice", o.call(arguments).join(","))
        },
        map: function(n) {
            return this.pushStack(i.map(this, function(t, i) {
                return n.call(t, i, t)
            }))
        },
        end: function() {
            return this.prevObject || this.constructor(null)
        },
        push: rr,
        sort: [].sort,
        splice: [].splice
    };
    i.fn.init.prototype = i.fn;
    i.extend = i.fn.extend = function() {
        var o, e, u, r, s, h, n = arguments[0] || {}, f = 1, l = arguments.length, c = !1;
        for (typeof n == "boolean" && (c = n,
        n = arguments[1] || {},
        f = 2),
        typeof n != "object" && !i.isFunction(n) && (n = {}),
        l === f && (n = this,
        --f); f < l; f++)
            if ((o = arguments[f]) != null)
                for (e in o)
                    (u = n[e],
                    r = o[e],
                    n !== r) && (c && r && (i.isPlainObject(r) || (s = i.isArray(r))) ? (s ? (s = !1,
                    h = u && i.isArray(u) ? u : []) : h = u && i.isPlainObject(u) ? u : {},
                    n[e] = i.extend(c, h, r)) : r !== t && (n[e] = r));
        return n
    }
    ;
    i.extend({
        noConflict: function(t) {
            return n.$ === i && (n.$ = ff),
            t && n.jQuery === i && (n.jQuery = uf),
            i
        },
        isReady: !1,
        readyWait: 1,
        holdReady: function(n) {
            n ? i.readyWait++ : i.ready(!0)
        },
        ready: function(n) {
            if (n === !0 ? !--i.readyWait : !i.isReady) {
                if (!r.body)
                    return setTimeout(i.ready, 1);
                (i.isReady = !0,
                n !== !0 && --i.readyWait > 0) || (ut.resolveWith(r, [i]),
                i.fn.trigger && i(r).trigger("ready").off("ready"))
            }
        },
        isFunction: function(n) {
            return i.type(n) === "function"
        },
        isArray: Array.isArray || function(n) {
            return i.type(n) === "array"
        }
        ,
        isWindow: function(n) {
            return n != null && n == n.window
        },
        isNumeric: function(n) {
            return !isNaN(parseFloat(n)) && isFinite(n)
        },
        type: function(n) {
            return n == null ? String(n) : er[ef.call(n)] || "object"
        },
        isPlainObject: function(n) {
            if (!n || i.type(n) !== "object" || n.nodeType || i.isWindow(n))
                return !1;
            try {
                if (n.constructor && !yt.call(n, "constructor") && !yt.call(n.constructor.prototype, "isPrototypeOf"))
                    return !1
            } catch (u) {
                return !1
            }
            for (var r in n)
                ;
            return r === t || yt.call(n, r)
        },
        isEmptyObject: function(n) {
            for (var t in n)
                return !1;
            return !0
        },
        error: function(n) {
            throw new Error(n);
        },
        parseHTML: function(n, t, u) {
            var f;
            return !n || typeof n != "string" ? null : (typeof t == "boolean" && (u = t,
            t = 0),
            t = t || r,
            (f = fr.exec(n)) ? [t.createElement(f[1])] : (f = i.buildFragment([n], t, u ? null : []),
            i.merge([], (f.cacheable ? i.clone(f.fragment) : f.fragment).childNodes)))
        },
        parseJSON: function(t) {
            if (!t || typeof t != "string")
                return null;
            if (t = i.trim(t),
            n.JSON && n.JSON.parse)
                return n.JSON.parse(t);
            if (cf.test(t.replace(af, "@").replace(vf, "]").replace(lf, "")))
                return new Function("return " + t)();
            i.error("Invalid JSON: " + t)
        },
        parseXML: function(r) {
            var u, f;
            if (!r || typeof r != "string")
                return null;
            try {
                n.DOMParser ? (f = new DOMParser,
                u = f.parseFromString(r, "text/xml")) : (u = new ActiveXObject("Microsoft.XMLDOM"),
                u.async = "false",
                u.loadXML(r))
            } catch (e) {
                u = t
            }
            return (!u || !u.documentElement || u.getElementsByTagName("parsererror").length) && i.error("Invalid XML: " + r),
            u
        },
        noop: function() {},
        globalEval: function(t) {
            t && of.test(t) && (n.execScript || function(t) {
                n.eval.call(n, t)
            }
            )(t)
        },
        camelCase: function(n) {
            return n.replace(yf, "ms-").replace(pf, wf)
        },
        nodeName: function(n, t) {
            return n.nodeName && n.nodeName.toLowerCase() === t.toLowerCase()
        },
        each: function(n, r, u) {
            var f, e = 0, o = n.length, s = o === t || i.isFunction(n);
            if (u) {
                if (s) {
                    for (f in n)
                        if (r.apply(n[f], u) === !1)
                            break
                } else
                    for (; e < o; )
                        if (r.apply(n[e++], u) === !1)
                            break
            } else if (s) {
                for (f in n)
                    if (r.call(n[f], f, n[f]) === !1)
                        break
            } else
                for (; e < o; )
                    if (r.call(n[e], e, n[e++]) === !1)
                        break;
            return n
        },
        trim: pt && !pt.call("﻿ ") ? function(n) {
            return n == null ? "" : pt.call(n)
        }
        : function(n) {
            return n == null ? "" : (n + "").replace(sf, "")
        }
        ,
        makeArray: function(n, t) {
            var r, u = t || [];
            return n != null && (r = i.type(n),
            n.length == null || r === "string" || r === "function" || r === "regexp" || i.isWindow(n) ? rr.call(u, n) : i.merge(u, n)),
            u
        },
        inArray: function(n, t, i) {
            var r;
            if (t) {
                if (ur)
                    return ur.call(t, n, i);
                for (r = t.length,
                i = i ? i < 0 ? Math.max(0, r + i) : i : 0; i < r; i++)
                    if (i in t && t[i] === n)
                        return i
            }
            return -1
        },
        merge: function(n, i) {
            var f = i.length
              , u = n.length
              , r = 0;
            if (typeof f == "number")
                for (; r < f; r++)
                    n[u++] = i[r];
            else
                while (i[r] !== t)
                    n[u++] = i[r++];
            return n.length = u,
            n
        },
        grep: function(n, t, i) {
            var u, f = [], r = 0, e = n.length;
            for (i = !!i; r < e; r++)
                u = !!t(n[r], r),
                i !== u && f.push(n[r]);
            return f
        },
        map: function(n, r, u) {
            var f, h, e = [], s = 0, o = n.length, c = n instanceof i || o !== t && typeof o == "number" && (o > 0 && n[0] && n[o - 1] || o === 0 || i.isArray(n));
            if (c)
                for (; s < o; s++)
                    f = r(n[s], s, u),
                    f != null && (e[e.length] = f);
            else
                for (h in n)
                    f = r(n[h], h, u),
                    f != null && (e[e.length] = f);
            return e.concat.apply([], e)
        },
        guid: 1,
        proxy: function(n, r) {
            var f, e, u;
            return typeof r == "string" && (f = n[r],
            r = n,
            n = f),
            i.isFunction(n) ? (e = o.call(arguments, 2),
            u = function() {
                return n.apply(r, e.concat(o.call(arguments)))
            }
            ,
            u.guid = n.guid = n.guid || i.guid++,
            u) : t
        },
        access: function(n, r, u, f, e, o, s) {
            var c, l = u == null, h = 0, a = n.length;
            if (u && typeof u == "object") {
                for (h in u)
                    i.access(n, r, h, u[h], 1, o, f);
                e = 1
            } else if (f !== t) {
                if (c = s === t && i.isFunction(f),
                l && (c ? (c = r,
                r = function(n, t, r) {
                    return c.call(i(n), r)
                }
                ) : (r.call(n, f),
                r = null)),
                r)
                    for (; h < a; h++)
                        r(n[h], u, c ? f.call(n[h], h, r(n[h], u)) : f, s);
                e = 1
            }
            return e ? n : l ? r.call(n) : a ? r(n[0], u) : o
        },
        now: function() {
            return (new Date).getTime()
        }
    });
    i.ready.promise = function(t) {
        if (!ut)
            if (ut = i.Deferred(),
            r.readyState === "complete")
                setTimeout(i.ready, 1);
            else if (r.addEventListener)
                r.addEventListener("DOMContentLoaded", et, !1),
                n.addEventListener("load", i.ready, !1);
            else {
                r.attachEvent("onreadystatechange", et);
                n.attachEvent("onload", i.ready);
                var u = !1;
                try {
                    u = n.frameElement == null && r.documentElement
                } catch (e) {}
                u && u.doScroll && function f() {
                    if (!i.isReady) {
                        try {
                            u.doScroll("left")
                        } catch (n) {
                            return setTimeout(f, 50)
                        }
                        i.ready()
                    }
                }()
            }
        return ut.promise(t)
    }
    ;
    i.each("Boolean Number String Function Array Date RegExp Object".split(" "), function(n, t) {
        er["[object " + t + "]"] = t.toLowerCase()
    });
    ir = i(r);
    wt = {};
    i.Callbacks = function(n) {
        n = typeof n == "string" ? wt[n] || yu(n) : i.extend({}, n);
        var f, c, o, l, s, e, r = [], u = !n.once && [], a = function(t) {
            for (f = n.memory && t,
            c = !0,
            e = l || 0,
            l = 0,
            s = r.length,
            o = !0; r && e < s; e++)
                if (r[e].apply(t[0], t[1]) === !1 && n.stopOnFalse) {
                    f = !1;
                    break
                }
            o = !1;
            r && (u ? u.length && a(u.shift()) : f ? r = [] : h.disable())
        }, h = {
            add: function() {
                if (r) {
                    var t = r.length;
                    (function u(t) {
                        i.each(t, function(t, f) {
                            var e = i.type(f);
                            e === "function" ? (!n.unique || !h.has(f)) && r.push(f) : f && f.length && e !== "string" && u(f)
                        })
                    }
                    )(arguments);
                    o ? s = r.length : f && (l = t,
                    a(f))
                }
                return this
            },
            remove: function() {
                return r && i.each(arguments, function(n, t) {
                    for (var u; (u = i.inArray(t, r, u)) > -1; )
                        r.splice(u, 1),
                        o && (u <= s && s--,
                        u <= e && e--)
                }),
                this
            },
            has: function(n) {
                return i.inArray(n, r) > -1
            },
            empty: function() {
                return r = [],
                this
            },
            disable: function() {
                return r = u = f = t,
                this
            },
            disabled: function() {
                return !r
            },
            lock: function() {
                return u = t,
                f || h.disable(),
                this
            },
            locked: function() {
                return !u
            },
            fireWith: function(n, t) {
                return t = t || [],
                t = [n, t.slice ? t.slice() : t],
                r && (!c || u) && (o ? u.push(t) : a(t)),
                this
            },
            fire: function() {
                return h.fireWith(this, arguments),
                this
            },
            fired: function() {
                return !!c
            }
        };
        return h
    }
    ;
    i.extend({
        Deferred: function(n) {
            var u = [["resolve", "done", i.Callbacks("once memory"), "resolved"], ["reject", "fail", i.Callbacks("once memory"), "rejected"], ["notify", "progress", i.Callbacks("memory")]]
              , f = "pending"
              , r = {
                state: function() {
                    return f
                },
                always: function() {
                    return t.done(arguments).fail(arguments),
                    this
                },
                then: function() {
                    var n = arguments;
                    return i.Deferred(function(r) {
                        i.each(u, function(u, f) {
                            var e = f[0]
                              , o = n[u];
                            t[f[1]](i.isFunction(o) ? function() {
                                var n = o.apply(this, arguments);
                                n && i.isFunction(n.promise) ? n.promise().done(r.resolve).fail(r.reject).progress(r.notify) : r[e + "With"](this === t ? r : this, [n])
                            }
                            : r[e])
                        });
                        n = null
                    }).promise()
                },
                promise: function(n) {
                    return n != null ? i.extend(n, r) : r
                }
            }
              , t = {};
            return r.pipe = r.then,
            i.each(u, function(n, i) {
                var e = i[2]
                  , o = i[3];
                r[i[1]] = e.add;
                o && e.add(function() {
                    f = o
                }, u[n ^ 1][2].disable, u[2][2].lock);
                t[i[0]] = e.fire;
                t[i[0] + "With"] = e.fireWith
            }),
            r.promise(t),
            n && n.call(t, t),
            t
        },
        when: function(n) {
            var t = 0, u = o.call(arguments), r = u.length, e = r !== 1 || n && i.isFunction(n.promise) ? r : 0, f = e === 1 ? n : i.Deferred(), c = function(n, t, i) {
                return function(r) {
                    t[n] = this;
                    i[n] = arguments.length > 1 ? o.call(arguments) : r;
                    i === s ? f.notifyWith(t, i) : --e || f.resolveWith(t, i)
                }
            }, s, l, h;
            if (r > 1)
                for (s = new Array(r),
                l = new Array(r),
                h = new Array(r); t < r; t++)
                    u[t] && i.isFunction(u[t].promise) ? u[t].promise().done(c(t, h, u)).fail(f.reject).progress(c(t, l, s)) : --e;
            return e || f.resolveWith(h, u),
            f.promise()
        }
    });
    i.support = function() {
        var u, h, e, c, l, f, o, a, v, s, y, t = r.createElement("div");
        if (t.setAttribute("className", "t"),
        t.innerHTML = "  <link/><table><\/table><a href='/a'>a<\/a><input type='checkbox'/>",
        h = t.getElementsByTagName("*"),
        e = t.getElementsByTagName("a")[0],
        !h || !e || !h.length)
            return {};
        c = r.createElement("select");
        l = c.appendChild(r.createElement("option"));
        f = t.getElementsByTagName("input")[0];
        e.style.cssText = "top:1px;float:left;opacity:.5";
        u = {
            leadingWhitespace: t.firstChild.nodeType === 3,
            tbody: !t.getElementsByTagName("tbody").length,
            htmlSerialize: !!t.getElementsByTagName("link").length,
            style: /top/.test(e.getAttribute("style")),
            hrefNormalized: e.getAttribute("href") === "/a",
            opacity: /^0.5/.test(e.style.opacity),
            cssFloat: !!e.style.cssFloat,
            checkOn: f.value === "on",
            optSelected: l.selected,
            getSetAttribute: t.className !== "t",
            enctype: !!r.createElement("form").enctype,
            html5Clone: r.createElement("nav").cloneNode(!0).outerHTML !== "<:nav><\/:nav>",
            boxModel: r.compatMode === "CSS1Compat",
            submitBubbles: !0,
            changeBubbles: !0,
            focusinBubbles: !1,
            deleteExpando: !0,
            noCloneEvent: !0,
            inlineBlockNeedsLayout: !1,
            shrinkWrapBlocks: !1,
            reliableMarginRight: !0,
            boxSizingReliable: !0,
            pixelPosition: !1
        };
        f.checked = !0;
        u.noCloneChecked = f.cloneNode(!0).checked;
        c.disabled = !0;
        u.optDisabled = !l.disabled;
        try {
            delete t.test
        } catch (p) {
            u.deleteExpando = !1
        }
        if (!t.addEventListener && t.attachEvent && t.fireEvent && (t.attachEvent("onclick", y = function() {
            u.noCloneEvent = !1
        }
        ),
        t.cloneNode(!0).fireEvent("onclick"),
        t.detachEvent("onclick", y)),
        f = r.createElement("input"),
        f.value = "t",
        f.setAttribute("type", "radio"),
        u.radioValue = f.value === "t",
        f.setAttribute("checked", "checked"),
        f.setAttribute("name", "t"),
        t.appendChild(f),
        o = r.createDocumentFragment(),
        o.appendChild(t.lastChild),
        u.checkClone = o.cloneNode(!0).cloneNode(!0).lastChild.checked,
        u.appendChecked = f.checked,
        o.removeChild(f),
        o.appendChild(t),
        t.attachEvent)
            for (v in {
                submit: !0,
                change: !0,
                focusin: !0
            })
                a = "on" + v,
                s = a in t,
                s || (t.setAttribute(a, "return;"),
                s = typeof t[a] == "function"),
                u[v + "Bubbles"] = s;
        return i(function() {
            var i, t, f, e, h = "padding:0;margin:0;border:0;display:block;overflow:hidden;", o = r.getElementsByTagName("body")[0];
            o && (i = r.createElement("div"),
            i.style.cssText = "visibility:hidden;border:0;width:0;height:0;position:static;top:0;margin-top:1px",
            o.insertBefore(i, o.firstChild),
            t = r.createElement("div"),
            i.appendChild(t),
            t.innerHTML = "<table><tr><td><\/td><td>t<\/td><\/tr><\/table>",
            f = t.getElementsByTagName("td"),
            f[0].style.cssText = "padding:0;margin:0;border:0;display:none",
            s = f[0].offsetHeight === 0,
            f[0].style.display = "",
            f[1].style.display = "none",
            u.reliableHiddenOffsets = s && f[0].offsetHeight === 0,
            t.innerHTML = "",
            t.style.cssText = "box-sizing:border-box;-moz-box-sizing:border-box;-webkit-box-sizing:border-box;padding:1px;border:1px;display:block;width:4px;margin-top:1%;position:absolute;top:1%;",
            u.boxSizing = t.offsetWidth === 4,
            u.doesNotIncludeMarginInBodyOffset = o.offsetTop !== 1,
            n.getComputedStyle && (u.pixelPosition = (n.getComputedStyle(t, null) || {}).top !== "1%",
            u.boxSizingReliable = (n.getComputedStyle(t, null) || {
                width: "4px"
            }).width === "4px",
            e = r.createElement("div"),
            e.style.cssText = t.style.cssText = h,
            e.style.marginRight = e.style.width = "0",
            t.style.width = "1px",
            t.appendChild(e),
            u.reliableMarginRight = !parseFloat((n.getComputedStyle(e, null) || {}).marginRight)),
            typeof t.style.zoom != "undefined" && (t.innerHTML = "",
            t.style.cssText = h + "width:1px;padding:1px;display:inline;zoom:1",
            u.inlineBlockNeedsLayout = t.offsetWidth === 3,
            t.style.display = "block",
            t.style.overflow = "visible",
            t.innerHTML = "<div><\/div>",
            t.firstChild.style.width = "5px",
            u.shrinkWrapBlocks = t.offsetWidth !== 3,
            i.style.zoom = 1),
            o.removeChild(i),
            i = t = f = e = null)
        }),
        o.removeChild(t),
        h = e = c = l = f = o = t = null,
        u
    }();
    or = /(?:\{[\s\S]*\}|\[[\s\S]*\])$/;
    sr = /([A-Z])/g;
    i.extend({
        cache: {},
        deletedIds: [],
        uuid: 0,
        expando: "jQuery" + (i.fn.jquery + Math.random()).replace(/\D/g, ""),
        noData: {
            embed: !0,
            object: "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",
            applet: !0
        },
        hasData: function(n) {
            return n = n.nodeType ? i.cache[n[i.expando]] : n[i.expando],
            !!n && !at(n)
        },
        data: function(n, r, u, f) {
            if (i.acceptData(n)) {
                var s, h, c = i.expando, a = typeof r == "string", l = n.nodeType, o = l ? i.cache : n, e = l ? n[c] : n[c] && c;
                if (e && o[e] && (f || o[e].data) || !a || u !== t)
                    return e || (l ? n[c] = e = i.deletedIds.pop() || i.guid++ : e = c),
                    o[e] || (o[e] = {},
                    l || (o[e].toJSON = i.noop)),
                    (typeof r == "object" || typeof r == "function") && (f ? o[e] = i.extend(o[e], r) : o[e].data = i.extend(o[e].data, r)),
                    s = o[e],
                    f || (s.data || (s.data = {}),
                    s = s.data),
                    u !== t && (s[i.camelCase(r)] = u),
                    a ? (h = s[r],
                    h == null && (h = s[i.camelCase(r)])) : h = s,
                    h
            }
        },
        removeData: function(n, t, r) {
            if (i.acceptData(n)) {
                var e, o, h, s = n.nodeType, u = s ? i.cache : n, f = s ? n[i.expando] : i.expando;
                if (u[f]) {
                    if (t && (e = r ? u[f] : u[f].data,
                    e)) {
                        for (i.isArray(t) || ((t in e) ? t = [t] : (t = i.camelCase(t),
                        t = (t in e) ? [t] : t.split(" "))),
                        o = 0,
                        h = t.length; o < h; o++)
                            delete e[t[o]];
                        if (!(r ? at : i.isEmptyObject)(e))
                            return
                    }
                    (r || (delete u[f].data,
                    at(u[f]))) && (s ? i.cleanData([n], !0) : i.support.deleteExpando || u != u.window ? delete u[f] : u[f] = null)
                }
            }
        },
        _data: function(n, t, r) {
            return i.data(n, t, r, !0)
        },
        acceptData: function(n) {
            var t = n.nodeName && i.noData[n.nodeName.toLowerCase()];
            return !t || t !== !0 && n.getAttribute("classid") === t
        }
    });
    i.fn.extend({
        data: function(n, r) {
            var u, s, h, o, l, e = this[0], c = 0, f = null;
            if (n === t) {
                if (this.length && (f = i.data(e),
                e.nodeType === 1 && !i._data(e, "parsedAttrs"))) {
                    for (h = e.attributes,
                    l = h.length; c < l; c++)
                        o = h[c].name,
                        o.indexOf("data-") || (o = i.camelCase(o.substring(5)),
                        ui(e, o, f[o]));
                    i._data(e, "parsedAttrs", !0)
                }
                return f
            }
            return typeof n == "object" ? this.each(function() {
                i.data(this, n)
            }) : (u = n.split(".", 2),
            u[1] = u[1] ? "." + u[1] : "",
            s = u[1] + "!",
            i.access(this, function(r) {
                if (r === t)
                    return f = this.triggerHandler("getData" + s, [u[0]]),
                    f === t && e && (f = i.data(e, n),
                    f = ui(e, n, f)),
                    f === t && u[1] ? this.data(u[0]) : f;
                u[1] = r;
                this.each(function() {
                    var t = i(this);
                    t.triggerHandler("setData" + s, u);
                    i.data(this, n, r);
                    t.triggerHandler("changeData" + s, u)
                })
            }, null, r, arguments.length > 1, null, !1))
        },
        removeData: function(n) {
            return this.each(function() {
                i.removeData(this, n)
            })
        }
    });
    i.extend({
        queue: function(n, t, r) {
            var u;
            if (n)
                return t = (t || "fx") + "queue",
                u = i._data(n, t),
                r && (!u || i.isArray(r) ? u = i._data(n, t, i.makeArray(r)) : u.push(r)),
                u || []
        },
        dequeue: function(n, t) {
            t = t || "fx";
            var r = i.queue(n, t)
              , e = r.length
              , u = r.shift()
              , f = i._queueHooks(n, t)
              , o = function() {
                i.dequeue(n, t)
            };
            u === "inprogress" && (u = r.shift(),
            e--);
            u && (t === "fx" && r.unshift("inprogress"),
            delete f.stop,
            u.call(n, o, f));
            !e && f && f.empty.fire()
        },
        _queueHooks: function(n, t) {
            var r = t + "queueHooks";
            return i._data(n, r) || i._data(n, r, {
                empty: i.Callbacks("once memory").add(function() {
                    i.removeData(n, t + "queue", !0);
                    i.removeData(n, r, !0)
                })
            })
        }
    });
    i.fn.extend({
        queue: function(n, r) {
            var u = 2;
            return typeof n != "string" && (r = n,
            n = "fx",
            u--),
            arguments.length < u ? i.queue(this[0], n) : r === t ? this : this.each(function() {
                var t = i.queue(this, n, r);
                i._queueHooks(this, n);
                n === "fx" && t[0] !== "inprogress" && i.dequeue(this, n)
            })
        },
        dequeue: function(n) {
            return this.each(function() {
                i.dequeue(this, n)
            })
        },
        delay: function(n, t) {
            return n = i.fx ? i.fx.speeds[n] || n : n,
            t = t || "fx",
            this.queue(t, function(t, i) {
                var r = setTimeout(t, n);
                i.stop = function() {
                    clearTimeout(r)
                }
            })
        },
        clearQueue: function(n) {
            return this.queue(n || "fx", [])
        },
        promise: function(n, r) {
            var u, e = 1, o = i.Deferred(), f = this, s = this.length, h = function() {
                --e || o.resolveWith(f, [f])
            };
            for (typeof n != "string" && (r = n,
            n = t),
            n = n || "fx"; s--; )
                u = i._data(f[s], n + "queueHooks"),
                u && u.empty && (e++,
                u.empty.add(h));
            return h(),
            o.promise(r)
        }
    });
    var s, hr, cr, lr = /[\t\r\n]/g, bf = /\r/g, kf = /^(?:button|input)$/i, df = /^(?:button|input|object|select|textarea)$/i, gf = /^a(?:rea|)$/i, ar = /^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i, vr = i.support.getSetAttribute;
    i.fn.extend({
        attr: function(n, t) {
            return i.access(this, i.attr, n, t, arguments.length > 1)
        },
        removeAttr: function(n) {
            return this.each(function() {
                i.removeAttr(this, n)
            })
        },
        prop: function(n, t) {
            return i.access(this, i.prop, n, t, arguments.length > 1)
        },
        removeProp: function(n) {
            return n = i.propFix[n] || n,
            this.each(function() {
                try {
                    this[n] = t;
                    delete this[n]
                } catch (i) {}
            })
        },
        addClass: function(n) {
            var r, f, o, t, e, u, s;
            if (i.isFunction(n))
                return this.each(function(t) {
                    i(this).addClass(n.call(this, t, this.className))
                });
            if (n && typeof n == "string")
                for (r = n.split(h),
                f = 0,
                o = this.length; f < o; f++)
                    if (t = this[f],
                    t.nodeType === 1)
                        if (t.className || r.length !== 1) {
                            for (e = " " + t.className + " ",
                            u = 0,
                            s = r.length; u < s; u++)
                                e.indexOf(" " + r[u] + " ") < 0 && (e += r[u] + " ");
                            t.className = i.trim(e)
                        } else
                            t.className = n;
            return this
        },
        removeClass: function(n) {
            var e, r, u, f, s, o, c;
            if (i.isFunction(n))
                return this.each(function(t) {
                    i(this).removeClass(n.call(this, t, this.className))
                });
            if (n && typeof n == "string" || n === t)
                for (e = (n || "").split(h),
                o = 0,
                c = this.length; o < c; o++)
                    if (u = this[o],
                    u.nodeType === 1 && u.className) {
                        for (r = (" " + u.className + " ").replace(lr, " "),
                        f = 0,
                        s = e.length; f < s; f++)
                            while (r.indexOf(" " + e[f] + " ") >= 0)
                                r = r.replace(" " + e[f] + " ", " ");
                        u.className = n ? i.trim(r) : ""
                    }
            return this
        },
        toggleClass: function(n, t) {
            var r = typeof n
              , u = typeof t == "boolean";
            return i.isFunction(n) ? this.each(function(r) {
                i(this).toggleClass(n.call(this, r, this.className, t), t)
            }) : this.each(function() {
                if (r === "string")
                    for (var f, s = 0, o = i(this), e = t, c = n.split(h); f = c[s++]; )
                        e = u ? e : !o.hasClass(f),
                        o[e ? "addClass" : "removeClass"](f);
                else
                    (r === "undefined" || r === "boolean") && (this.className && i._data(this, "__className__", this.className),
                    this.className = this.className || n === !1 ? "" : i._data(this, "__className__") || "")
            })
        },
        hasClass: function(n) {
            for (var i = " " + n + " ", t = 0, r = this.length; t < r; t++)
                if (this[t].nodeType === 1 && (" " + this[t].className + " ").replace(lr, " ").indexOf(i) >= 0)
                    return !0;
            return !1
        },
        val: function(n) {
            var r, u, e, f = this[0];
            return arguments.length ? (e = i.isFunction(n),
            this.each(function(u) {
                var f, o = i(this);
                this.nodeType === 1 && (f = e ? n.call(this, u, o.val()) : n,
                f == null ? f = "" : typeof f == "number" ? f += "" : i.isArray(f) && (f = i.map(f, function(n) {
                    return n == null ? "" : n + ""
                })),
                r = i.valHooks[this.type] || i.valHooks[this.nodeName.toLowerCase()],
                r && "set"in r && r.set(this, f, "value") !== t || (this.value = f))
            })) : f ? (r = i.valHooks[f.type] || i.valHooks[f.nodeName.toLowerCase()],
            r && "get"in r && (u = r.get(f, "value")) !== t ? u : (u = f.value,
            typeof u == "string" ? u.replace(bf, "") : u == null ? "" : u)) : void 0
        }
    });
    i.extend({
        valHooks: {
            option: {
                get: function(n) {
                    var t = n.attributes.value;
                    return !t || t.specified ? n.value : n.text
                }
            },
            select: {
                get: function(n) {
                    for (var e, t, o = n.options, r = n.selectedIndex, u = n.type === "select-one" || r < 0, s = u ? null : [], h = u ? r + 1 : o.length, f = r < 0 ? h : u ? r : 0; f < h; f++)
                        if (t = o[f],
                        (t.selected || f === r) && (i.support.optDisabled ? !t.disabled : t.getAttribute("disabled") === null) && (!t.parentNode.disabled || !i.nodeName(t.parentNode, "optgroup"))) {
                            if (e = i(t).val(),
                            u)
                                return e;
                            s.push(e)
                        }
                    return s
                },
                set: function(n, t) {
                    var r = i.makeArray(t);
                    return i(n).find("option").each(function() {
                        this.selected = i.inArray(i(this).val(), r) >= 0
                    }),
                    r.length || (n.selectedIndex = -1),
                    r
                }
            }
        },
        attrFn: {},
        attr: function(n, r, u, f) {
            var e, o, h, c = n.nodeType;
            if (n && c !== 3 && c !== 8 && c !== 2) {
                if (f && i.isFunction(i.fn[r]))
                    return i(n)[r](u);
                if (typeof n.getAttribute == "undefined")
                    return i.prop(n, r, u);
                if (h = c !== 1 || !i.isXMLDoc(n),
                h && (r = r.toLowerCase(),
                o = i.attrHooks[r] || (ar.test(r) ? hr : s)),
                u !== t) {
                    if (u === null) {
                        i.removeAttr(n, r);
                        return
                    }
                    return o && "set"in o && h && (e = o.set(n, u, r)) !== t ? e : (n.setAttribute(r, u + ""),
                    u)
                }
                return o && "get"in o && h && (e = o.get(n, r)) !== null ? e : (e = n.getAttribute(r),
                e === null ? t : e)
            }
        },
        removeAttr: function(n, t) {
            var u, f, r, e, o = 0;
            if (t && n.nodeType === 1)
                for (f = t.split(h); o < f.length; o++)
                    r = f[o],
                    r && (u = i.propFix[r] || r,
                    e = ar.test(r),
                    e || i.attr(n, r, ""),
                    n.removeAttribute(vr ? r : u),
                    e && u in n && (n[u] = !1))
        },
        attrHooks: {
            type: {
                set: function(n, t) {
                    if (kf.test(n.nodeName) && n.parentNode)
                        i.error("type property can't be changed");
                    else if (!i.support.radioValue && t === "radio" && i.nodeName(n, "input")) {
                        var r = n.value;
                        return n.setAttribute("type", t),
                        r && (n.value = r),
                        t
                    }
                }
            },
            value: {
                get: function(n, t) {
                    return s && i.nodeName(n, "button") ? s.get(n, t) : t in n ? n.value : null
                },
                set: function(n, t, r) {
                    if (s && i.nodeName(n, "button"))
                        return s.set(n, t, r);
                    n.value = t
                }
            }
        },
        propFix: {
            tabindex: "tabIndex",
            readonly: "readOnly",
            "for": "htmlFor",
            "class": "className",
            maxlength: "maxLength",
            cellspacing: "cellSpacing",
            cellpadding: "cellPadding",
            rowspan: "rowSpan",
            colspan: "colSpan",
            usemap: "useMap",
            frameborder: "frameBorder",
            contenteditable: "contentEditable"
        },
        prop: function(n, r, u) {
            var e, f, s, o = n.nodeType;
            if (n && o !== 3 && o !== 8 && o !== 2)
                return s = o !== 1 || !i.isXMLDoc(n),
                s && (r = i.propFix[r] || r,
                f = i.propHooks[r]),
                u !== t ? f && "set"in f && (e = f.set(n, u, r)) !== t ? e : n[r] = u : f && "get"in f && (e = f.get(n, r)) !== null ? e : n[r]
        },
        propHooks: {
            tabIndex: {
                get: function(n) {
                    var i = n.getAttributeNode("tabindex");
                    return i && i.specified ? parseInt(i.value, 10) : df.test(n.nodeName) || gf.test(n.nodeName) && n.href ? 0 : t
                }
            }
        }
    });
    hr = {
        get: function(n, r) {
            var u, f = i.prop(n, r);
            return f === !0 || typeof f != "boolean" && (u = n.getAttributeNode(r)) && u.nodeValue !== !1 ? r.toLowerCase() : t
        },
        set: function(n, t, r) {
            var u;
            return t === !1 ? i.removeAttr(n, r) : (u = i.propFix[r] || r,
            u in n && (n[u] = !0),
            n.setAttribute(r, r.toLowerCase())),
            r
        }
    };
    vr || (cr = {
        name: !0,
        id: !0,
        coords: !0
    },
    s = i.valHooks.button = {
        get: function(n, i) {
            var r;
            return r = n.getAttributeNode(i),
            r && (cr[i] ? r.value !== "" : r.specified) ? r.value : t
        },
        set: function(n, t, i) {
            var u = n.getAttributeNode(i);
            return u || (u = r.createAttribute(i),
            n.setAttributeNode(u)),
            u.value = t + ""
        }
    },
    i.each(["width", "height"], function(n, t) {
        i.attrHooks[t] = i.extend(i.attrHooks[t], {
            set: function(n, i) {
                if (i === "")
                    return n.setAttribute(t, "auto"),
                    i
            }
        })
    }),
    i.attrHooks.contenteditable = {
        get: s.get,
        set: function(n, t, i) {
            t === "" && (t = "false");
            s.set(n, t, i)
        }
    });
    i.support.hrefNormalized || i.each(["href", "src", "width", "height"], function(n, r) {
        i.attrHooks[r] = i.extend(i.attrHooks[r], {
            get: function(n) {
                var i = n.getAttribute(r, 2);
                return i === null ? t : i
            }
        })
    });
    i.support.style || (i.attrHooks.style = {
        get: function(n) {
            return n.style.cssText.toLowerCase() || t
        },
        set: function(n, t) {
            return n.style.cssText = t + ""
        }
    });
    i.support.optSelected || (i.propHooks.selected = i.extend(i.propHooks.selected, {
        get: function(n) {
            var t = n.parentNode;
            return t && (t.selectedIndex,
            t.parentNode && t.parentNode.selectedIndex),
            null
        }
    }));
    i.support.enctype || (i.propFix.enctype = "encoding");
    i.support.checkOn || i.each(["radio", "checkbox"], function() {
        i.valHooks[this] = {
            get: function(n) {
                return n.getAttribute("value") === null ? "on" : n.value
            }
        }
    });
    i.each(["radio", "checkbox"], function() {
        i.valHooks[this] = i.extend(i.valHooks[this], {
            set: function(n, t) {
                if (i.isArray(t))
                    return n.checked = i.inArray(i(n).val(), t) >= 0
            }
        })
    });
    var bt = /^(?:textarea|input|select)$/i
      , yr = /^([^\.]*|)(?:\.(.+)|)$/
      , ne = /(?:^|\s)hover(\.\S+|)\b/
      , te = /^key/
      , ie = /^(?:mouse|contextmenu)|click/
      , pr = /^(?:focusinfocus|focusoutblur)$/
      , wr = function(n) {
        return i.event.special.hover ? n : n.replace(ne, "mouseenter$1 mouseleave$1")
    };
    i.event = {
        add: function(n, r, u, f, e) {
            var a, s, v, y, p, o, b, l, w, c, h;
            if (n.nodeType !== 3 && n.nodeType !== 8 && r && u && (a = i._data(n))) {
                for (u.handler && (w = u,
                u = w.handler,
                e = w.selector),
                u.guid || (u.guid = i.guid++),
                v = a.events,
                v || (a.events = v = {}),
                s = a.handle,
                s || (a.handle = s = function(n) {
                    return typeof i == "undefined" || !!n && i.event.triggered === n.type ? t : i.event.dispatch.apply(s.elem, arguments)
                }
                ,
                s.elem = n),
                r = i.trim(wr(r)).split(" "),
                y = 0; y < r.length; y++)
                    p = yr.exec(r[y]) || [],
                    o = p[1],
                    b = (p[2] || "").split(".").sort(),
                    h = i.event.special[o] || {},
                    o = (e ? h.delegateType : h.bindType) || o,
                    h = i.event.special[o] || {},
                    l = i.extend({
                        type: o,
                        origType: p[1],
                        data: f,
                        handler: u,
                        guid: u.guid,
                        selector: e,
                        needsContext: e && i.expr.match.needsContext.test(e),
                        namespace: b.join(".")
                    }, w),
                    c = v[o],
                    c || (c = v[o] = [],
                    c.delegateCount = 0,
                    h.setup && h.setup.call(n, f, b, s) !== !1 || (n.addEventListener ? n.addEventListener(o, s, !1) : n.attachEvent && n.attachEvent("on" + o, s))),
                    h.add && (h.add.call(n, l),
                    l.handler.guid || (l.handler.guid = u.guid)),
                    e ? c.splice(c.delegateCount++, 0, l) : c.push(l),
                    i.event.global[o] = !0;
                n = null
            }
        },
        global: {},
        remove: function(n, t, r, u, f) {
            var l, p, e, w, h, b, a, v, c, o, s, y = i.hasData(n) && i._data(n);
            if (y && (v = y.events)) {
                for (t = i.trim(wr(t || "")).split(" "),
                l = 0; l < t.length; l++) {
                    if (p = yr.exec(t[l]) || [],
                    e = w = p[1],
                    h = p[2],
                    !e) {
                        for (e in v)
                            i.event.remove(n, e + t[l], r, u, !0);
                        continue
                    }
                    for (c = i.event.special[e] || {},
                    e = (u ? c.delegateType : c.bindType) || e,
                    o = v[e] || [],
                    b = o.length,
                    h = h ? new RegExp("(^|\\.)" + h.split(".").sort().join("\\.(?:.*\\.|)") + "(\\.|$)") : null,
                    a = 0; a < o.length; a++)
                        s = o[a],
                        (f || w === s.origType) && (!r || r.guid === s.guid) && (!h || h.test(s.namespace)) && (!u || u === s.selector || u === "**" && s.selector) && (o.splice(a--, 1),
                        s.selector && o.delegateCount--,
                        c.remove && c.remove.call(n, s));
                    o.length === 0 && b !== o.length && ((!c.teardown || c.teardown.call(n, h, y.handle) === !1) && i.removeEvent(n, e, y.handle),
                    delete v[e])
                }
                i.isEmptyObject(v) && (delete y.handle,
                i.removeData(n, "events", !0))
            }
        },
        customEvent: {
            getData: !0,
            setData: !0,
            changeData: !0
        },
        trigger: function(u, f, e, o) {
            if (!e || e.nodeType !== 3 && e.nodeType !== 8) {
                var w, d, c, h, l, v, a, y, p, k, s = u.type || u, b = [];
                if (pr.test(s + i.event.triggered))
                    return;
                if (s.indexOf("!") >= 0 && (s = s.slice(0, -1),
                d = !0),
                s.indexOf(".") >= 0 && (b = s.split("."),
                s = b.shift(),
                b.sort()),
                (!e || i.event.customEvent[s]) && !i.event.global[s])
                    return;
                if (u = typeof u == "object" ? u[i.expando] ? u : new i.Event(s,u) : new i.Event(s),
                u.type = s,
                u.isTrigger = !0,
                u.exclusive = d,
                u.namespace = b.join("."),
                u.namespace_re = u.namespace ? new RegExp("(^|\\.)" + b.join("\\.(?:.*\\.|)") + "(\\.|$)") : null,
                v = s.indexOf(":") < 0 ? "on" + s : "",
                !e) {
                    w = i.cache;
                    for (c in w)
                        w[c].events && w[c].events[s] && i.event.trigger(u, f, w[c].handle.elem, !0);
                    return
                }
                if (u.result = t,
                u.target || (u.target = e),
                f = f != null ? i.makeArray(f) : [],
                f.unshift(u),
                a = i.event.special[s] || {},
                a.trigger && a.trigger.apply(e, f) === !1)
                    return;
                if (p = [[e, a.bindType || s]],
                !o && !a.noBubble && !i.isWindow(e)) {
                    for (k = a.delegateType || s,
                    h = pr.test(k + s) ? e : e.parentNode,
                    l = e; h; h = h.parentNode)
                        p.push([h, k]),
                        l = h;
                    l === (e.ownerDocument || r) && p.push([l.defaultView || l.parentWindow || n, k])
                }
                for (c = 0; c < p.length && !u.isPropagationStopped(); c++)
                    h = p[c][0],
                    u.type = p[c][1],
                    y = (i._data(h, "events") || {})[u.type] && i._data(h, "handle"),
                    y && y.apply(h, f),
                    y = v && h[v],
                    y && i.acceptData(h) && y.apply && y.apply(h, f) === !1 && u.preventDefault();
                return u.type = s,
                !o && !u.isDefaultPrevented() && (!a._default || a._default.apply(e.ownerDocument, f) === !1) && (s !== "click" || !i.nodeName(e, "a")) && i.acceptData(e) && v && e[s] && (s !== "focus" && s !== "blur" || u.target.offsetWidth !== 0) && !i.isWindow(e) && (l = e[v],
                l && (e[v] = null),
                i.event.triggered = s,
                e[s](),
                i.event.triggered = t,
                l && (e[v] = l)),
                u.result
            }
            return
        },
        dispatch: function(r) {
            r = i.event.fix(r || n.event);
            var f, c, e, l, a, h, v, u, s, y = (i._data(this, "events") || {})[r.type] || [], p = y.delegateCount, k = o.call(arguments), d = !r.exclusive && !r.namespace, w = i.event.special[r.type] || {}, b = [];
            if (k[0] = r,
            r.delegateTarget = this,
            !w.preDispatch || w.preDispatch.call(this, r) !== !1) {
                if (p && (!r.button || r.type !== "click"))
                    for (e = r.target; e != this; e = e.parentNode || this)
                        if (e.disabled !== !0 || r.type !== "click") {
                            for (a = {},
                            v = [],
                            f = 0; f < p; f++)
                                u = y[f],
                                s = u.selector,
                                a[s] === t && (a[s] = u.needsContext ? i(s, this).index(e) >= 0 : i.find(s, this, null, [e]).length),
                                a[s] && v.push(u);
                            v.length && b.push({
                                elem: e,
                                matches: v
                            })
                        }
                for (y.length > p && b.push({
                    elem: this,
                    matches: y.slice(p)
                }),
                f = 0; f < b.length && !r.isPropagationStopped(); f++)
                    for (h = b[f],
                    r.currentTarget = h.elem,
                    c = 0; c < h.matches.length && !r.isImmediatePropagationStopped(); c++)
                        u = h.matches[c],
                        (d || !r.namespace && !u.namespace || r.namespace_re && r.namespace_re.test(u.namespace)) && (r.data = u.data,
                        r.handleObj = u,
                        l = ((i.event.special[u.origType] || {}).handle || u.handler).apply(h.elem, k),
                        l !== t && (r.result = l,
                        l === !1 && (r.preventDefault(),
                        r.stopPropagation())));
                return w.postDispatch && w.postDispatch.call(this, r),
                r.result
            }
        },
        props: "attrChange attrName relatedNode srcElement altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "),
            filter: function(n, t) {
                return n.which == null && (n.which = t.charCode != null ? t.charCode : t.keyCode),
                n
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function(n, i) {
                var o, u, f, e = i.button, s = i.fromElement;
                return n.pageX == null && i.clientX != null && (o = n.target.ownerDocument || r,
                u = o.documentElement,
                f = o.body,
                n.pageX = i.clientX + (u && u.scrollLeft || f && f.scrollLeft || 0) - (u && u.clientLeft || f && f.clientLeft || 0),
                n.pageY = i.clientY + (u && u.scrollTop || f && f.scrollTop || 0) - (u && u.clientTop || f && f.clientTop || 0)),
                !n.relatedTarget && s && (n.relatedTarget = s === n.target ? i.toElement : s),
                !n.which && e !== t && (n.which = e & 1 ? 1 : e & 2 ? 3 : e & 4 ? 2 : 0),
                n
            }
        },
        fix: function(n) {
            if (n[i.expando])
                return n;
            var f, e, t = n, u = i.event.fixHooks[n.type] || {}, o = u.props ? this.props.concat(u.props) : this.props;
            for (n = i.Event(t),
            f = o.length; f; )
                e = o[--f],
                n[e] = t[e];
            return n.target || (n.target = t.srcElement || r),
            n.target.nodeType === 3 && (n.target = n.target.parentNode),
            n.metaKey = !!n.metaKey,
            u.filter ? u.filter(n, t) : n
        },
        special: {
            load: {
                noBubble: !0
            },
            focus: {
                delegateType: "focusin"
            },
            blur: {
                delegateType: "focusout"
            },
            beforeunload: {
                setup: function(n, t, r) {
                    i.isWindow(this) && (this.onbeforeunload = r)
                },
                teardown: function(n, t) {
                    this.onbeforeunload === t && (this.onbeforeunload = null)
                }
            }
        },
        simulate: function(n, t, r, u) {
            var f = i.extend(new i.Event, r, {
                type: n,
                isSimulated: !0,
                originalEvent: {}
            });
            u ? i.event.trigger(f, null, t) : i.event.dispatch.call(t, f);
            f.isDefaultPrevented() && r.preventDefault()
        }
    };
    i.event.handle = i.event.dispatch;
    i.removeEvent = r.removeEventListener ? function(n, t, i) {
        n.removeEventListener && n.removeEventListener(t, i, !1)
    }
    : function(n, t, i) {
        var r = "on" + t;
        n.detachEvent && (typeof n[r] == "undefined" && (n[r] = null),
        n.detachEvent(r, i))
    }
    ;
    i.Event = function(n, t) {
        if (!(this instanceof i.Event))
            return new i.Event(n,t);
        n && n.type ? (this.originalEvent = n,
        this.type = n.type,
        this.isDefaultPrevented = n.defaultPrevented || n.returnValue === !1 || n.getPreventDefault && n.getPreventDefault() ? g : v) : this.type = n;
        t && i.extend(this, t);
        this.timeStamp = n && n.timeStamp || i.now();
        this[i.expando] = !0
    }
    ;
    i.Event.prototype = {
        preventDefault: function() {
            this.isDefaultPrevented = g;
            var n = this.originalEvent;
            n && (n.preventDefault ? n.preventDefault() : n.returnValue = !1)
        },
        stopPropagation: function() {
            this.isPropagationStopped = g;
            var n = this.originalEvent;
            n && (n.stopPropagation && n.stopPropagation(),
            n.cancelBubble = !0)
        },
        stopImmediatePropagation: function() {
            this.isImmediatePropagationStopped = g;
            this.stopPropagation()
        },
        isDefaultPrevented: v,
        isPropagationStopped: v,
        isImmediatePropagationStopped: v
    };
    i.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout"
    }, function(n, t) {
        i.event.special[n] = {
            delegateType: t,
            bindType: t,
            handle: function(n) {
                var f, e = this, r = n.relatedTarget, u = n.handleObj, o = u.selector;
                return r && (r === e || i.contains(e, r)) || (n.type = u.origType,
                f = u.handler.apply(this, arguments),
                n.type = t),
                f
            }
        }
    });
    i.support.submitBubbles || (i.event.special.submit = {
        setup: function() {
            if (i.nodeName(this, "form"))
                return !1;
            i.event.add(this, "click._submit keypress._submit", function(n) {
                var u = n.target
                  , r = i.nodeName(u, "input") || i.nodeName(u, "button") ? u.form : t;
                r && !i._data(r, "_submit_attached") && (i.event.add(r, "submit._submit", function(n) {
                    n._submit_bubble = !0
                }),
                i._data(r, "_submit_attached", !0))
            })
        },
        postDispatch: function(n) {
            n._submit_bubble && (delete n._submit_bubble,
            this.parentNode && !n.isTrigger && i.event.simulate("submit", this.parentNode, n, !0))
        },
        teardown: function() {
            if (i.nodeName(this, "form"))
                return !1;
            i.event.remove(this, "._submit")
        }
    });
    i.support.changeBubbles || (i.event.special.change = {
        setup: function() {
            if (bt.test(this.nodeName))
                return (this.type === "checkbox" || this.type === "radio") && (i.event.add(this, "propertychange._change", function(n) {
                    n.originalEvent.propertyName === "checked" && (this._just_changed = !0)
                }),
                i.event.add(this, "click._change", function(n) {
                    this._just_changed && !n.isTrigger && (this._just_changed = !1);
                    i.event.simulate("change", this, n, !0)
                })),
                !1;
            i.event.add(this, "beforeactivate._change", function(n) {
                var t = n.target;
                bt.test(t.nodeName) && !i._data(t, "_change_attached") && (i.event.add(t, "change._change", function(n) {
                    !this.parentNode || n.isSimulated || n.isTrigger || i.event.simulate("change", this.parentNode, n, !0)
                }),
                i._data(t, "_change_attached", !0))
            })
        },
        handle: function(n) {
            var t = n.target;
            if (this !== t || n.isSimulated || n.isTrigger || t.type !== "radio" && t.type !== "checkbox")
                return n.handleObj.handler.apply(this, arguments)
        },
        teardown: function() {
            return i.event.remove(this, "._change"),
            !bt.test(this.nodeName)
        }
    });
    i.support.focusinBubbles || i.each({
        focus: "focusin",
        blur: "focusout"
    }, function(n, t) {
        var u = 0
          , f = function(n) {
            i.event.simulate(t, n.target, i.event.fix(n), !0)
        };
        i.event.special[t] = {
            setup: function() {
                u++ == 0 && r.addEventListener(n, f, !0)
            },
            teardown: function() {
                --u == 0 && r.removeEventListener(n, f, !0)
            }
        }
    });
    i.fn.extend({
        on: function(n, r, u, f, e) {
            var o, s;
            if (typeof n == "object") {
                typeof r != "string" && (u = u || r,
                r = t);
                for (s in n)
                    this.on(s, r, u, n[s], e);
                return this
            }
            if (u == null && f == null ? (f = r,
            u = r = t) : f == null && (typeof r == "string" ? (f = u,
            u = t) : (f = u,
            u = r,
            r = t)),
            f === !1)
                f = v;
            else if (!f)
                return this;
            return e === 1 && (o = f,
            f = function(n) {
                return i().off(n),
                o.apply(this, arguments)
            }
            ,
            f.guid = o.guid || (o.guid = i.guid++)),
            this.each(function() {
                i.event.add(this, n, f, u, r)
            })
        },
        one: function(n, t, i, r) {
            return this.on(n, t, i, r, 1)
        },
        off: function(n, r, u) {
            var f, e;
            if (n && n.preventDefault && n.handleObj)
                return f = n.handleObj,
                i(n.delegateTarget).off(f.namespace ? f.origType + "." + f.namespace : f.origType, f.selector, f.handler),
                this;
            if (typeof n == "object") {
                for (e in n)
                    this.off(e, r, n[e]);
                return this
            }
            return (r === !1 || typeof r == "function") && (u = r,
            r = t),
            u === !1 && (u = v),
            this.each(function() {
                i.event.remove(this, n, u, r)
            })
        },
        bind: function(n, t, i) {
            return this.on(n, null, t, i)
        },
        unbind: function(n, t) {
            return this.off(n, null, t)
        },
        live: function(n, t, r) {
            return i(this.context).on(n, this.selector, t, r),
            this
        },
        die: function(n, t) {
            return i(this.context).off(n, this.selector || "**", t),
            this
        },
        delegate: function(n, t, i, r) {
            return this.on(t, n, i, r)
        },
        undelegate: function(n, t, i) {
            return arguments.length === 1 ? this.off(n, "**") : this.off(t, n || "**", i)
        },
        trigger: function(n, t) {
            return this.each(function() {
                i.event.trigger(n, t, this)
            })
        },
        triggerHandler: function(n, t) {
            if (this[0])
                return i.event.trigger(n, t, this[0], !0)
        },
        toggle: function(n) {
            var t = arguments
              , u = n.guid || i.guid++
              , r = 0
              , f = function(u) {
                var f = (i._data(this, "lastToggle" + n.guid) || 0) % r;
                return i._data(this, "lastToggle" + n.guid, f + 1),
                u.preventDefault(),
                t[f].apply(this, arguments) || !1
            };
            for (f.guid = u; r < t.length; )
                t[r++].guid = u;
            return this.click(f)
        },
        hover: function(n, t) {
            return this.mouseenter(n).mouseleave(t || n)
        }
    });
    i.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function(n, t) {
        i.fn[t] = function(n, i) {
            return i == null && (i = n,
            n = null),
            arguments.length > 0 ? this.on(t, null, n, i) : this.trigger(t)
        }
        ;
        te.test(t) && (i.event.fixHooks[t] = i.event.keyHooks);
        ie.test(t) && (i.event.fixHooks[t] = i.event.mouseHooks)
    }),
    function(n, t) {
        function r(n, t, i, r) {
            i = i || [];
            t = t || h;
            var e, u, o, f, s = t.nodeType;
            if (!n || typeof n != "string")
                return i;
            if (s !== 1 && s !== 9)
                return [];
            if (o = it(t),
            !o && !r && (e = ki.exec(n)))
                if (f = e[1]) {
                    if (s === 9) {
                        if (u = t.getElementById(f),
                        !u || !u.parentNode)
                            return i;
                        if (u.id === f)
                            return i.push(u),
                            i
                    } else if (t.ownerDocument && (u = t.ownerDocument.getElementById(f)) && ti(t, u) && u.id === f)
                        return i.push(u),
                        i
                } else {
                    if (e[2])
                        return p.apply(i, w.call(t.getElementsByTagName(n), 0)),
                        i;
                    if ((f = e[3]) && hi && t.getElementsByClassName)
                        return p.apply(i, w.call(t.getElementsByClassName(f), 0)),
                        i
                }
            return lt(n.replace(ft, "$1"), t, i, r, o)
        }
        function b(n) {
            return function(t) {
                var i = t.nodeName.toLowerCase();
                return i === "input" && t.type === n
            }
        }
        function gt(n) {
            return function(t) {
                var i = t.nodeName.toLowerCase();
                return (i === "input" || i === "button") && t.type === n
            }
        }
        function a(n) {
            return s(function(t) {
                return t = +t,
                s(function(i, r) {
                    for (var u, f = n([], i.length, t), e = f.length; e--; )
                        i[u = f[e]] && (i[u] = !(r[u] = i[u]))
                })
            })
        }
        function d(n, t, i) {
            if (n === t)
                return i;
            for (var r = n.nextSibling; r; ) {
                if (r === t)
                    return -1;
                r = r.nextSibling
            }
            return 1
        }
        function g(n, t) {
            var o, f, h, s, i, c, l, a = fi[e][n + " "];
            if (a)
                return t ? 0 : a.slice(0);
            for (i = n,
            c = [],
            l = u.preFilter; i; ) {
                (!o || (f = pi.exec(i))) && (f && (i = i.slice(f[0].length) || i),
                c.push(h = []));
                o = !1;
                (f = wi.exec(i)) && (h.push(o = new ri(f.shift())),
                i = i.slice(o.length),
                o.type = f[0].replace(ft, " "));
                for (s in u.filter)
                    (f = et[s].exec(i)) && (!l[s] || (f = l[s](f))) && (h.push(o = new ri(f.shift())),
                    i = i.slice(o.length),
                    o.type = s,
                    o.matches = f);
                if (!o)
                    break
            }
            return t ? i.length : i ? r.error(n) : fi(n, c).slice(0)
        }
        function ot(n, t, i) {
            var r = t.dir
              , u = i && t.dir === "parentNode"
              , f = ai++;
            return t.first ? function(t, i, f) {
                while (t = t[r])
                    if (u || t.nodeType === 1)
                        return n(t, i, f)
            }
            : function(t, i, o) {
                if (o) {
                    while (t = t[r])
                        if ((u || t.nodeType === 1) && n(t, i, o))
                            return t
                } else
                    for (var s, h = ut + " " + f + " ", c = h + at; t = t[r]; )
                        if (u || t.nodeType === 1) {
                            if ((s = t[e]) === c)
                                return t.sizset;
                            if (typeof s == "string" && s.indexOf(h) === 0) {
                                if (t.sizset)
                                    return t
                            } else {
                                if (t[e] = c,
                                n(t, i, o))
                                    return t.sizset = !0,
                                    t;
                                t.sizset = !1
                            }
                        }
            }
        }
        function st(n) {
            return n.length > 1 ? function(t, i, r) {
                for (var u = n.length; u--; )
                    if (!n[u](t, i, r))
                        return !1;
                return !0
            }
            : n[0]
        }
        function nt(n, t, i, r, u) {
            for (var e, o = [], f = 0, s = n.length, h = t != null; f < s; f++)
                (e = n[f]) && (!i || i(e, r, u)) && (o.push(e),
                h && t.push(f));
            return o
        }
        function ht(n, t, i, r, u, f) {
            return r && !r[e] && (r = ht(r)),
            u && !u[e] && (u = ht(u, f)),
            s(function(f, e, o, s) {
                var l, c, a, w = [], y = [], b = e.length, k = f || li(t || "*", o.nodeType ? [o] : o, []), v = n && (f || !t) ? nt(k, w, n, o, s) : k, h = i ? u || (f ? n : b || r) ? [] : e : v;
                if (i && i(v, h, o, s),
                r)
                    for (l = nt(h, y),
                    r(l, [], o, s),
                    c = l.length; c--; )
                        (a = l[c]) && (h[y[c]] = !(v[y[c]] = a));
                if (f) {
                    if (u || n) {
                        if (u) {
                            for (l = [],
                            c = h.length; c--; )
                                (a = h[c]) && l.push(v[c] = a);
                            u(null, h = [], l, s)
                        }
                        for (c = h.length; c--; )
                            (a = h[c]) && (l = u ? wt.call(f, a) : w[c]) > -1 && (f[l] = !(e[l] = a))
                    }
                } else
                    h = nt(h === e ? h.splice(b, h.length) : h),
                    u ? u(null, e, h, s) : p.apply(e, h)
            })
        }
        function ct(n) {
            for (var s, r, i, o = n.length, h = u.relative[n[0].type], c = h || u.relative[" "], t = h ? 1 : 0, l = ot(function(n) {
                return n === s
            }, c, !0), a = ot(function(n) {
                return wt.call(s, n) > -1
            }, c, !0), f = [function(n, t, i) {
                return !h && (i || t !== rt) || ((s = t).nodeType ? l(n, t, i) : a(n, t, i))
            }
            ]; t < o; t++)
                if (r = u.relative[n[t].type])
                    f = [ot(st(f), r)];
                else {
                    if (r = u.filter[n[t].type].apply(null, n[t].matches),
                    r[e]) {
                        for (i = ++t; i < o; i++)
                            if (u.relative[n[i].type])
                                break;
                        return ht(t > 1 && st(f), t > 1 && n.slice(0, t - 1).join("").replace(ft, "$1"), r, t < i && ct(n.slice(t, i)), i < o && ct(n = n.slice(i)), i < o && n.join(""))
                    }
                    f.push(r)
                }
            return st(f)
        }
        function ci(n, t) {
            var f = t.length > 0
              , e = n.length > 0
              , i = function(o, s, c, l, a) {
                var y, b, k, w = [], d = 0, v = "0", g = o && [], tt = a != null, it = rt, et = o || e && u.find.TAG("*", a && s.parentNode || s), ft = ut += it == null ? 1 : Math.E;
                for (tt && (rt = s !== h && s,
                at = i.el); (y = et[v]) != null; v++) {
                    if (e && y) {
                        for (b = 0; k = n[b]; b++)
                            if (k(y, s, c)) {
                                l.push(y);
                                break
                            }
                        tt && (ut = ft,
                        at = ++i.el)
                    }
                    f && ((y = !k && y) && d--,
                    o && g.push(y))
                }
                if (d += v,
                f && v !== d) {
                    for (b = 0; k = t[b]; b++)
                        k(g, w, s, c);
                    if (o) {
                        if (d > 0)
                            while (v--)
                                g[v] || w[v] || (w[v] = vi.call(l));
                        w = nt(w)
                    }
                    p.apply(l, w);
                    tt && !o && w.length > 0 && d + t.length > 1 && r.uniqueSort(l)
                }
                return tt && (ut = ft,
                rt = it),
                g
            };
            return i.el = 0,
            f ? s(i) : i
        }
        function li(n, t, i) {
            for (var u = 0, f = t.length; u < f; u++)
                r(n, t[u], i);
            return i
        }
        function lt(n, t, i, r, f) {
            var o, e, s, c, l, h = g(n), a = h.length;
            if (!r && h.length === 1) {
                if (e = h[0] = h[0].slice(0),
                e.length > 2 && (s = e[0]).type === "ID" && t.nodeType === 9 && !f && u.relative[e[1].type]) {
                    if (t = u.find.ID(s.matches[0].replace(y, ""), t, f)[0],
                    !t)
                        return i;
                    n = n.slice(e.shift().length)
                }
                for (o = et.POS.test(n) ? -1 : e.length - 1; o >= 0; o--) {
                    if (s = e[o],
                    u.relative[c = s.type])
                        break;
                    if ((l = u.find[c]) && (r = l(s.matches[0].replace(y, ""), dt.test(e[0].type) && t.parentNode || t, f))) {
                        if (e.splice(o, 1),
                        n = r.length && e.join(""),
                        !n)
                            return p.apply(i, w.call(r, 0)),
                            i;
                        break
                    }
                }
            }
            return yt(n, h)(r, t, f, i, dt.test(n)),
            i
        }
        function ni() {}
        var at, vt, u, tt, it, ti, yt, pt, k, rt, ii = !0, c = "undefined", e = ("sizcache" + Math.random()).replace(".", ""), ri = String, h = n.document, o = h.documentElement, ut = 0, ai = 0, vi = [].pop, p = [].push, w = [].slice, wt = [].indexOf || function(n) {
            for (var t = 0, i = this.length; t < i; t++)
                if (this[t] === n)
                    return t;
            return -1
        }
        , s = function(n, t) {
            return n[e] = t == null || t,
            n
        }, bt = function() {
            var n = {}
              , t = [];
            return s(function(i, r) {
                return t.push(i) > u.cacheLength && delete n[t.shift()],
                n[i + " "] = r
            }, n)
        }, ui = bt(), fi = bt(), ei = bt(), f = "[\\x20\\t\\r\\n\\f]", v = "(?:\\\\.|[-\\w]|[^\\x00-\\xa0])+", yi = v.replace("w", "w#"), oi = "\\[" + f + "*(" + v + ")" + f + "*(?:([*^$|!~]?=)" + f + "*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|(" + yi + ")|)|)" + f + "*\\]", kt = ":(" + v + ")(?:\\((?:(['\"])((?:\\\\.|[^\\\\])*?)\\2|([^()[\\]]*|(?:(?:" + oi + ")|[^:]|\\\\.)*|.*))\\)|)", si = ":(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + f + "*((?:-\\d)?\\d*)" + f + "*\\)|)(?=[^-]|$)", ft = new RegExp("^" + f + "+|((?:^|[^\\\\])(?:\\\\.)*)" + f + "+$","g"), pi = new RegExp("^" + f + "*," + f + "*"), wi = new RegExp("^" + f + "*([\\x20\\t\\r\\n\\f>+~])" + f + "*"), bi = new RegExp(kt), ki = /^(?:#([\w\-]+)|(\w+)|\.([\w\-]+))$/, dt = /[\x20\t\r\n\f]*[+~]/, di = /h\d/i, gi = /input|select|textarea|button/i, y = /\\(?!\\)/g, et = {
            ID: new RegExp("^#(" + v + ")"),
            CLASS: new RegExp("^\\.(" + v + ")"),
            NAME: new RegExp("^\\[name=['\"]?(" + v + ")['\"]?\\]"),
            TAG: new RegExp("^(" + v.replace("w", "w*") + ")"),
            ATTR: new RegExp("^" + oi),
            PSEUDO: new RegExp("^" + kt),
            POS: new RegExp(si,"i"),
            CHILD: new RegExp("^:(only|nth|first|last)-child(?:\\(" + f + "*(even|odd|(([+-]|)(\\d*)n|)" + f + "*(?:([+-]|)" + f + "*(\\d+)|))" + f + "*\\)|)","i"),
            needsContext: new RegExp("^" + f + "*[>+~]|" + si,"i")
        }, l = function(n) {
            var t = h.createElement("div");
            try {
                return n(t)
            } catch (i) {
                return !1
            } finally {
                t = null
            }
        }, nr = l(function(n) {
            return n.appendChild(h.createComment("")),
            !n.getElementsByTagName("*").length
        }), tr = l(function(n) {
            return n.innerHTML = "<a href='#'><\/a>",
            n.firstChild && typeof n.firstChild.getAttribute !== c && n.firstChild.getAttribute("href") === "#"
        }), ir = l(function(n) {
            n.innerHTML = "<select><\/select>";
            var t = typeof n.lastChild.getAttribute("multiple");
            return t !== "boolean" && t !== "string"
        }), hi = l(function(n) {
            return n.innerHTML = "<div class='hidden e'><\/div><div class='hidden'><\/div>",
            !n.getElementsByClassName || !n.getElementsByClassName("e").length ? !1 : (n.lastChild.className = "e",
            n.getElementsByClassName("e").length === 2)
        }), rr = l(function(n) {
            n.id = e + 0;
            n.innerHTML = "<a name='" + e + "'><\/a><div name='" + e + "'><\/div>";
            o.insertBefore(n, o.firstChild);
            var t = h.getElementsByName && h.getElementsByName(e).length === 2 + h.getElementsByName(e + 0).length;
            return vt = !h.getElementById(e),
            o.removeChild(n),
            t
        });
        try {
            w.call(o.childNodes, 0)[0].nodeType
        } catch (ur) {
            w = function(n) {
                for (var t, i = []; t = this[n]; n++)
                    i.push(t);
                return i
            }
        }
        r.matches = function(n, t) {
            return r(n, null, null, t)
        }
        ;
        r.matchesSelector = function(n, t) {
            return r(t, null, null, [n]).length > 0
        }
        ;
        tt = r.getText = function(n) {
            var r, i = "", u = 0, t = n.nodeType;
            if (t) {
                if (t === 1 || t === 9 || t === 11) {
                    if (typeof n.textContent == "string")
                        return n.textContent;
                    for (n = n.firstChild; n; n = n.nextSibling)
                        i += tt(n)
                } else if (t === 3 || t === 4)
                    return n.nodeValue
            } else
                for (; r = n[u]; u++)
                    i += tt(r);
            return i
        }
        ;
        it = r.isXML = function(n) {
            var t = n && (n.ownerDocument || n).documentElement;
            return t ? t.nodeName !== "HTML" : !1
        }
        ;
        ti = r.contains = o.contains ? function(n, t) {
            var r = n.nodeType === 9 ? n.documentElement : n
              , i = t && t.parentNode;
            return n === i || !!(i && i.nodeType === 1 && r.contains && r.contains(i))
        }
        : o.compareDocumentPosition ? function(n, t) {
            return t && !!(n.compareDocumentPosition(t) & 16)
        }
        : function(n, t) {
            while (t = t.parentNode)
                if (t === n)
                    return !0;
            return !1
        }
        ;
        r.attr = function(n, t) {
            var i, r = it(n);
            return r || (t = t.toLowerCase()),
            (i = u.attrHandle[t]) ? i(n) : r || ir ? n.getAttribute(t) : (i = n.getAttributeNode(t),
            i ? typeof n[t] == "boolean" ? n[t] ? t : null : i.specified ? i.value : null : null)
        }
        ;
        u = r.selectors = {
            cacheLength: 50,
            createPseudo: s,
            match: et,
            attrHandle: tr ? {} : {
                href: function(n) {
                    return n.getAttribute("href", 2)
                },
                type: function(n) {
                    return n.getAttribute("type")
                }
            },
            find: {
                ID: vt ? function(n, t, i) {
                    if (typeof t.getElementById !== c && !i) {
                        var r = t.getElementById(n);
                        return r && r.parentNode ? [r] : []
                    }
                }
                : function(n, i, r) {
                    if (typeof i.getElementById !== c && !r) {
                        var u = i.getElementById(n);
                        return u ? u.id === n || typeof u.getAttributeNode !== c && u.getAttributeNode("id").value === n ? [u] : t : []
                    }
                }
                ,
                TAG: nr ? function(n, t) {
                    if (typeof t.getElementsByTagName !== c)
                        return t.getElementsByTagName(n)
                }
                : function(n, t) {
                    var f = t.getElementsByTagName(n), i, r, u;
                    if (n === "*") {
                        for (r = [],
                        u = 0; i = f[u]; u++)
                            i.nodeType === 1 && r.push(i);
                        return r
                    }
                    return f
                }
                ,
                NAME: rr && function(n, t) {
                    if (typeof t.getElementsByName !== c)
                        return t.getElementsByName(name)
                }
                ,
                CLASS: hi && function(n, t, i) {
                    if (typeof t.getElementsByClassName !== c && !i)
                        return t.getElementsByClassName(n)
                }
            },
            relative: {
                ">": {
                    dir: "parentNode",
                    first: !0
                },
                " ": {
                    dir: "parentNode"
                },
                "+": {
                    dir: "previousSibling",
                    first: !0
                },
                "~": {
                    dir: "previousSibling"
                }
            },
            preFilter: {
                ATTR: function(n) {
                    return n[1] = n[1].replace(y, ""),
                    n[3] = (n[4] || n[5] || "").replace(y, ""),
                    n[2] === "~=" && (n[3] = " " + n[3] + " "),
                    n.slice(0, 4)
                },
                CHILD: function(n) {
                    return n[1] = n[1].toLowerCase(),
                    n[1] === "nth" ? (n[2] || r.error(n[0]),
                    n[3] = +(n[3] ? n[4] + (n[5] || 1) : 2 * (n[2] === "even" || n[2] === "odd")),
                    n[4] = +(n[6] + n[7] || n[2] === "odd")) : n[2] && r.error(n[0]),
                    n
                },
                PSEUDO: function(n) {
                    var t, i;
                    return et.CHILD.test(n[0]) ? null : (n[3] ? n[2] = n[3] : (t = n[4]) && (bi.test(t) && (i = g(t, !0)) && (i = t.indexOf(")", t.length - i) - t.length) && (t = t.slice(0, i),
                    n[0] = n[0].slice(0, i)),
                    n[2] = t),
                    n.slice(0, 3))
                }
            },
            filter: {
                ID: vt ? function(n) {
                    return n = n.replace(y, ""),
                    function(t) {
                        return t.getAttribute("id") === n
                    }
                }
                : function(n) {
                    return n = n.replace(y, ""),
                    function(t) {
                        var i = typeof t.getAttributeNode !== c && t.getAttributeNode("id");
                        return i && i.value === n
                    }
                }
                ,
                TAG: function(n) {
                    return n === "*" ? function() {
                        return !0
                    }
                    : (n = n.replace(y, "").toLowerCase(),
                    function(t) {
                        return t.nodeName && t.nodeName.toLowerCase() === n
                    }
                    )
                },
                CLASS: function(n) {
                    var t = ui[e][n + " "];
                    return t || (t = new RegExp("(^|" + f + ")" + n + "(" + f + "|$)")) && ui(n, function(n) {
                        return t.test(n.className || typeof n.getAttribute !== c && n.getAttribute("class") || "")
                    })
                },
                ATTR: function(n, t, i) {
                    return function(u) {
                        var f = r.attr(u, n);
                        return f == null ? t === "!=" : t ? (f += "",
                        t === "=" ? f === i : t === "!=" ? f !== i : t === "^=" ? i && f.indexOf(i) === 0 : t === "*=" ? i && f.indexOf(i) > -1 : t === "$=" ? i && f.substr(f.length - i.length) === i : t === "~=" ? (" " + f + " ").indexOf(i) > -1 : t === "|=" ? f === i || f.substr(0, i.length + 1) === i + "-" : !1) : !0
                    }
                },
                CHILD: function(n, t, i, r) {
                    return n === "nth" ? function(n) {
                        var t, u, f = n.parentNode;
                        if (i === 1 && r === 0)
                            return !0;
                        if (f)
                            for (u = 0,
                            t = f.firstChild; t; t = t.nextSibling)
                                if (t.nodeType === 1 && (u++,
                                n === t))
                                    break;
                        return u -= r,
                        u === i || u % i == 0 && u / i >= 0
                    }
                    : function(t) {
                        var i = t;
                        switch (n) {
                        case "only":
                        case "first":
                            while (i = i.previousSibling)
                                if (i.nodeType === 1)
                                    return !1;
                            if (n === "first")
                                return !0;
                            i = t;
                        case "last":
                            while (i = i.nextSibling)
                                if (i.nodeType === 1)
                                    return !1;
                            return !0
                        }
                    }
                },
                PSEUDO: function(n, t) {
                    var f, i = u.pseudos[n] || u.setFilters[n.toLowerCase()] || r.error("unsupported pseudo: " + n);
                    return i[e] ? i(t) : i.length > 1 ? (f = [n, n, "", t],
                    u.setFilters.hasOwnProperty(n.toLowerCase()) ? s(function(n, r) {
                        for (var u, f = i(n, t), e = f.length; e--; )
                            u = wt.call(n, f[e]),
                            n[u] = !(r[u] = f[e])
                    }) : function(n) {
                        return i(n, 0, f)
                    }
                    ) : i
                }
            },
            pseudos: {
                not: s(function(n) {
                    var i = []
                      , r = []
                      , t = yt(n.replace(ft, "$1"));
                    return t[e] ? s(function(n, i, r, u) {
                        for (var e, o = t(n, null, u, []), f = n.length; f--; )
                            (e = o[f]) && (n[f] = !(i[f] = e))
                    }) : function(n, u, f) {
                        return i[0] = n,
                        t(i, null, f, r),
                        !r.pop()
                    }
                }),
                has: s(function(n) {
                    return function(t) {
                        return r(n, t).length > 0
                    }
                }),
                contains: s(function(n) {
                    return function(t) {
                        return (t.textContent || t.innerText || tt(t)).indexOf(n) > -1
                    }
                }),
                enabled: function(n) {
                    return n.disabled === !1
                },
                disabled: function(n) {
                    return n.disabled === !0
                },
                checked: function(n) {
                    var t = n.nodeName.toLowerCase();
                    return t === "input" && !!n.checked || t === "option" && !!n.selected
                },
                selected: function(n) {
                    return n.parentNode && n.parentNode.selectedIndex,
                    n.selected === !0
                },
                parent: function(n) {
                    return !u.pseudos.empty(n)
                },
                empty: function(n) {
                    var t;
                    for (n = n.firstChild; n; ) {
                        if (n.nodeName > "@" || (t = n.nodeType) === 3 || t === 4)
                            return !1;
                        n = n.nextSibling
                    }
                    return !0
                },
                header: function(n) {
                    return di.test(n.nodeName)
                },
                text: function(n) {
                    var t, i;
                    return n.nodeName.toLowerCase() === "input" && (t = n.type) === "text" && ((i = n.getAttribute("type")) == null || i.toLowerCase() === t)
                },
                radio: b("radio"),
                checkbox: b("checkbox"),
                file: b("file"),
                password: b("password"),
                image: b("image"),
                submit: gt("submit"),
                reset: gt("reset"),
                button: function(n) {
                    var t = n.nodeName.toLowerCase();
                    return t === "input" && n.type === "button" || t === "button"
                },
                input: function(n) {
                    return gi.test(n.nodeName)
                },
                focus: function(n) {
                    var t = n.ownerDocument;
                    return n === t.activeElement && (!t.hasFocus || t.hasFocus()) && !!(n.type || n.href || ~n.tabIndex)
                },
                active: function(n) {
                    return n === n.ownerDocument.activeElement
                },
                first: a(function() {
                    return [0]
                }),
                last: a(function(n, t) {
                    return [t - 1]
                }),
                eq: a(function(n, t, i) {
                    return [i < 0 ? i + t : i]
                }),
                even: a(function(n, t) {
                    for (var i = 0; i < t; i += 2)
                        n.push(i);
                    return n
                }),
                odd: a(function(n, t) {
                    for (var i = 1; i < t; i += 2)
                        n.push(i);
                    return n
                }),
                lt: a(function(n, t, i) {
                    for (var r = i < 0 ? i + t : i; --r >= 0; )
                        n.push(r);
                    return n
                }),
                gt: a(function(n, t, i) {
                    for (var r = i < 0 ? i + t : i; ++r < t; )
                        n.push(r);
                    return n
                })
            }
        };
        pt = o.compareDocumentPosition ? function(n, t) {
            return n === t ? (k = !0,
            0) : (!n.compareDocumentPosition || !t.compareDocumentPosition ? n.compareDocumentPosition : n.compareDocumentPosition(t) & 4) ? -1 : 1
        }
        : function(n, t) {
            var i;
            if (n === t)
                return k = !0,
                0;
            if (n.sourceIndex && t.sourceIndex)
                return n.sourceIndex - t.sourceIndex;
            var e, h, u = [], f = [], o = n.parentNode, s = t.parentNode, r = o;
            if (o === s)
                return d(n, t);
            if (!o)
                return -1;
            if (!s)
                return 1;
            while (r)
                u.unshift(r),
                r = r.parentNode;
            for (r = s; r; )
                f.unshift(r),
                r = r.parentNode;
            for (e = u.length,
            h = f.length,
            i = 0; i < e && i < h; i++)
                if (u[i] !== f[i])
                    return d(u[i], f[i]);
            return i === e ? d(n, f[i], -1) : d(u[i], t, 1)
        }
        ;
        [0, 0].sort(pt);
        ii = !k;
        r.uniqueSort = function(n) {
            var r, u = [], t = 1, i = 0;
            if (k = ii,
            n.sort(pt),
            k) {
                for (; r = n[t]; t++)
                    r === n[t - 1] && (i = u.push(t));
                while (i--)
                    n.splice(u[i], 1)
            }
            return n
        }
        ;
        r.error = function(n) {
            throw new Error("Syntax error, unrecognized expression: " + n);
        }
        ;
        yt = r.compile = function(n, t) {
            var r, u = [], f = [], i = ei[e][n + " "];
            if (!i) {
                for (t || (t = g(n)),
                r = t.length; r--; )
                    i = ct(t[r]),
                    i[e] ? u.push(i) : f.push(i);
                i = ei(n, ci(f, u))
            }
            return i
        }
        ;
        h.querySelectorAll && function() {
            var u, s = lt, h = /'|\\/g, c = /\=[\x20\t\r\n\f]*([^'"\]]*)[\x20\t\r\n\f]*\]/g, n = [":focus"], t = [":active"], i = o.matchesSelector || o.mozMatchesSelector || o.webkitMatchesSelector || o.oMatchesSelector || o.msMatchesSelector;
            l(function(t) {
                t.innerHTML = "<select><option selected=''><\/option><\/select>";
                t.querySelectorAll("[selected]").length || n.push("\\[" + f + "*(?:checked|disabled|ismap|multiple|readonly|selected|value)");
                t.querySelectorAll(":checked").length || n.push(":checked")
            });
            l(function(t) {
                t.innerHTML = "<p test=''><\/p>";
                t.querySelectorAll("[test^='']").length && n.push("[*^$]=" + f + "*(?:\"\"|'')");
                t.innerHTML = "<input type='hidden'/>";
                t.querySelectorAll(":enabled").length || n.push(":enabled", ":disabled")
            });
            n = new RegExp(n.join("|"));
            lt = function(t, i, r, u, f) {
                if (!u && !f && !n.test(t)) {
                    var o, l, a = !0, c = e, y = i, v = i.nodeType === 9 && t;
                    if (i.nodeType === 1 && i.nodeName.toLowerCase() !== "object") {
                        for (o = g(t),
                        (a = i.getAttribute("id")) ? c = a.replace(h, "\\$&") : i.setAttribute("id", c),
                        c = "[id='" + c + "'] ",
                        l = o.length; l--; )
                            o[l] = c + o[l].join("");
                        y = dt.test(t) && i.parentNode || i;
                        v = o.join(",")
                    }
                    if (v)
                        try {
                            return p.apply(r, w.call(y.querySelectorAll(v), 0)),
                            r
                        } catch (b) {} finally {
                            a || i.removeAttribute("id")
                        }
                }
                return s(t, i, r, u, f)
            }
            ;
            i && (l(function(n) {
                u = i.call(n, "div");
                try {
                    i.call(n, "[test!='']:sizzle");
                    t.push("!=", kt)
                } catch (r) {}
            }),
            t = new RegExp(t.join("|")),
            r.matchesSelector = function(f, e) {
                if (e = e.replace(c, "='$1']"),
                !it(f) && !t.test(e) && !n.test(e))
                    try {
                        var o = i.call(f, e);
                        if (o || u || f.document && f.document.nodeType !== 11)
                            return o
                    } catch (s) {}
                return r(e, null, null, [f]).length > 0
            }
            )
        }();
        u.pseudos.nth = u.pseudos.eq;
        u.filters = ni.prototype = u.pseudos;
        u.setFilters = new ni;
        r.attr = i.attr;
        i.find = r;
        i.expr = r.selectors;
        i.expr[":"] = i.expr.pseudos;
        i.unique = r.uniqueSort;
        i.text = r.getText;
        i.isXMLDoc = r.isXML;
        i.contains = r.contains
    }(n);
    var re = /Until$/
      , ue = /^(?:parents|prev(?:Until|All))/
      , fe = /^.[^:#\[\.,]*$/
      , br = i.expr.match.needsContext
      , ee = {
        children: !0,
        contents: !0,
        next: !0,
        prev: !0
    };
    i.fn.extend({
        find: function(n) {
            var t, f, o, u, e, r, s = this;
            if (typeof n != "string")
                return i(n).filter(function() {
                    for (t = 0,
                    f = s.length; t < f; t++)
                        if (i.contains(s[t], this))
                            return !0
                });
            for (r = this.pushStack("", "find", n),
            t = 0,
            f = this.length; t < f; t++)
                if (o = r.length,
                i.find(n, this[t], r),
                t > 0)
                    for (u = o; u < r.length; u++)
                        for (e = 0; e < o; e++)
                            if (r[e] === r[u]) {
                                r.splice(u--, 1);
                                break
                            }
            return r
        },
        has: function(n) {
            var t, r = i(n, this), u = r.length;
            return this.filter(function() {
                for (t = 0; t < u; t++)
                    if (i.contains(this, r[t]))
                        return !0
            })
        },
        not: function(n) {
            return this.pushStack(ei(this, n, !1), "not", n)
        },
        filter: function(n) {
            return this.pushStack(ei(this, n, !0), "filter", n)
        },
        is: function(n) {
            return !!n && (typeof n == "string" ? br.test(n) ? i(n, this.context).index(this[0]) >= 0 : i.filter(n, this).length > 0 : this.filter(n).length > 0)
        },
        closest: function(n, t) {
            for (var r, f = 0, o = this.length, u = [], e = br.test(n) || typeof n != "string" ? i(n, t || this.context) : 0; f < o; f++)
                for (r = this[f]; r && r.ownerDocument && r !== t && r.nodeType !== 11; ) {
                    if (e ? e.index(r) > -1 : i.find.matchesSelector(r, n)) {
                        u.push(r);
                        break
                    }
                    r = r.parentNode
                }
            return u = u.length > 1 ? i.unique(u) : u,
            this.pushStack(u, "closest", n)
        },
        index: function(n) {
            return n ? typeof n == "string" ? i.inArray(this[0], i(n)) : i.inArray(n.jquery ? n[0] : n, this) : this[0] && this[0].parentNode ? this.prevAll().length : -1
        },
        add: function(n, t) {
            var u = typeof n == "string" ? i(n, t) : i.makeArray(n && n.nodeType ? [n] : n)
              , r = i.merge(this.get(), u);
            return this.pushStack(k(u[0]) || k(r[0]) ? r : i.unique(r))
        },
        addBack: function(n) {
            return this.add(n == null ? this.prevObject : this.prevObject.filter(n))
        }
    });
    i.fn.andSelf = i.fn.addBack;
    i.each({
        parent: function(n) {
            var t = n.parentNode;
            return t && t.nodeType !== 11 ? t : null
        },
        parents: function(n) {
            return i.dir(n, "parentNode")
        },
        parentsUntil: function(n, t, r) {
            return i.dir(n, "parentNode", r)
        },
        next: function(n) {
            return fi(n, "nextSibling")
        },
        prev: function(n) {
            return fi(n, "previousSibling")
        },
        nextAll: function(n) {
            return i.dir(n, "nextSibling")
        },
        prevAll: function(n) {
            return i.dir(n, "previousSibling")
        },
        nextUntil: function(n, t, r) {
            return i.dir(n, "nextSibling", r)
        },
        prevUntil: function(n, t, r) {
            return i.dir(n, "previousSibling", r)
        },
        siblings: function(n) {
            return i.sibling((n.parentNode || {}).firstChild, n)
        },
        children: function(n) {
            return i.sibling(n.firstChild)
        },
        contents: function(n) {
            return i.nodeName(n, "iframe") ? n.contentDocument || n.contentWindow.document : i.merge([], n.childNodes)
        }
    }, function(n, t) {
        i.fn[n] = function(r, u) {
            var f = i.map(this, t, r);
            return re.test(n) || (u = r),
            u && typeof u == "string" && (f = i.filter(u, f)),
            f = this.length > 1 && !ee[n] ? i.unique(f) : f,
            this.length > 1 && ue.test(n) && (f = f.reverse()),
            this.pushStack(f, n, o.call(arguments).join(","))
        }
    });
    i.extend({
        filter: function(n, t, r) {
            return r && (n = ":not(" + n + ")"),
            t.length === 1 ? i.find.matchesSelector(t[0], n) ? [t[0]] : [] : i.find.matches(n, t)
        },
        dir: function(n, r, u) {
            for (var e = [], f = n[r]; f && f.nodeType !== 9 && (u === t || f.nodeType !== 1 || !i(f).is(u)); )
                f.nodeType === 1 && e.push(f),
                f = f[r];
            return e
        },
        sibling: function(n, t) {
            for (var i = []; n; n = n.nextSibling)
                n.nodeType === 1 && n !== t && i.push(n);
            return i
        }
    });
    var kr = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video"
      , oe = / jQuery\d+="(?:null|\d+)"/g
      , kt = /^\s+/
      , dr = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi
      , gr = /<([\w:]+)/
      , se = /<tbody/i
      , he = /<|&#?\w+;/
      , ce = /<(?:script|style|link)/i
      , le = /<(?:script|object|embed|option|style)/i
      , dt = new RegExp("<(?:" + kr + ")[\\s/>]","i")
      , nu = /^(?:checkbox|radio)$/
      , tu = /checked\s*(?:[^=]|=\s*.checked.)/i
      , ae = /\/(java|ecma)script/i
      , ve = /^\s*<!(?:\[CDATA\[|\-\-)|[\]\-]{2}>\s*$/g
      , e = {
        option: [1, "<select multiple='multiple'>", "<\/select>"],
        legend: [1, "<fieldset>", "<\/fieldset>"],
        thead: [1, "<table>", "<\/table>"],
        tr: [2, "<table><tbody>", "<\/tbody><\/table>"],
        td: [3, "<table><tbody><tr>", "<\/tr><\/tbody><\/table>"],
        col: [2, "<table><tbody><\/tbody><colgroup>", "<\/colgroup><\/table>"],
        area: [1, "<map>", "<\/map>"],
        _default: [0, "", ""]
    }
      , iu = oi(r)
      , gt = iu.appendChild(r.createElement("div"));
    e.optgroup = e.option;
    e.tbody = e.tfoot = e.colgroup = e.caption = e.thead;
    e.th = e.td;
    i.support.htmlSerialize || (e._default = [1, "X<div>", "<\/div>"]);
    i.fn.extend({
        text: function(n) {
            return i.access(this, function(n) {
                return n === t ? i.text(this) : this.empty().append((this[0] && this[0].ownerDocument || r).createTextNode(n))
            }, null, n, arguments.length)
        },
        wrapAll: function(n) {
            if (i.isFunction(n))
                return this.each(function(t) {
                    i(this).wrapAll(n.call(this, t))
                });
            if (this[0]) {
                var t = i(n, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && t.insertBefore(this[0]);
                t.map(function() {
                    for (var n = this; n.firstChild && n.firstChild.nodeType === 1; )
                        n = n.firstChild;
                    return n
                }).append(this)
            }
            return this
        },
        wrapInner: function(n) {
            return i.isFunction(n) ? this.each(function(t) {
                i(this).wrapInner(n.call(this, t))
            }) : this.each(function() {
                var t = i(this)
                  , r = t.contents();
                r.length ? r.wrapAll(n) : t.append(n)
            })
        },
        wrap: function(n) {
            var t = i.isFunction(n);
            return this.each(function(r) {
                i(this).wrapAll(t ? n.call(this, r) : n)
            })
        },
        unwrap: function() {
            return this.parent().each(function() {
                i.nodeName(this, "body") || i(this).replaceWith(this.childNodes)
            }).end()
        },
        append: function() {
            return this.domManip(arguments, !0, function(n) {
                (this.nodeType === 1 || this.nodeType === 11) && this.appendChild(n)
            })
        },
        prepend: function() {
            return this.domManip(arguments, !0, function(n) {
                (this.nodeType === 1 || this.nodeType === 11) && this.insertBefore(n, this.firstChild)
            })
        },
        before: function() {
            if (!k(this[0]))
                return this.domManip(arguments, !1, function(n) {
                    this.parentNode.insertBefore(n, this)
                });
            if (arguments.length) {
                var n = i.clean(arguments);
                return this.pushStack(i.merge(n, this), "before", this.selector)
            }
        },
        after: function() {
            if (!k(this[0]))
                return this.domManip(arguments, !1, function(n) {
                    this.parentNode.insertBefore(n, this.nextSibling)
                });
            if (arguments.length) {
                var n = i.clean(arguments);
                return this.pushStack(i.merge(this, n), "after", this.selector)
            }
        },
        remove: function(n, t) {
            for (var r, u = 0; (r = this[u]) != null; u++)
                (!n || i.filter(n, [r]).length) && (t || r.nodeType !== 1 || (i.cleanData(r.getElementsByTagName("*")),
                i.cleanData([r])),
                r.parentNode && r.parentNode.removeChild(r));
            return this
        },
        empty: function() {
            for (var n, t = 0; (n = this[t]) != null; t++)
                for (n.nodeType === 1 && i.cleanData(n.getElementsByTagName("*")); n.firstChild; )
                    n.removeChild(n.firstChild);
            return this
        },
        clone: function(n, t) {
            return n = n == null ? !1 : n,
            t = t == null ? n : t,
            this.map(function() {
                return i.clone(this, n, t)
            })
        },
        html: function(n) {
            return i.access(this, function(n) {
                var r = this[0] || {}
                  , u = 0
                  , f = this.length;
                if (n === t)
                    return r.nodeType === 1 ? r.innerHTML.replace(oe, "") : t;
                if (typeof n == "string" && !ce.test(n) && (i.support.htmlSerialize || !dt.test(n)) && (i.support.leadingWhitespace || !kt.test(n)) && !e[(gr.exec(n) || ["", ""])[1].toLowerCase()]) {
                    n = n.replace(dr, "<$1><\/$2>");
                    try {
                        for (; u < f; u++)
                            r = this[u] || {},
                            r.nodeType === 1 && (i.cleanData(r.getElementsByTagName("*")),
                            r.innerHTML = n);
                        r = 0
                    } catch (o) {}
                }
                r && this.empty().append(n)
            }, null, n, arguments.length)
        },
        replaceWith: function(n) {
            return k(this[0]) ? this.length ? this.pushStack(i(i.isFunction(n) ? n() : n), "replaceWith", n) : this : i.isFunction(n) ? this.each(function(t) {
                var r = i(this)
                  , u = r.html();
                r.replaceWith(n.call(this, t, u))
            }) : (typeof n != "string" && (n = i(n).detach()),
            this.each(function() {
                var t = this.nextSibling
                  , r = this.parentNode;
                i(this).remove();
                t ? i(t).before(n) : i(r).append(n)
            }))
        },
        detach: function(n) {
            return this.remove(n, !0)
        },
        domManip: function(n, r, u) {
            n = [].concat.apply([], n);
            var h, o, f, a, e = 0, s = n[0], c = [], l = this.length;
            if (!i.support.checkClone && l > 1 && typeof s == "string" && tu.test(s))
                return this.each(function() {
                    i(this).domManip(n, r, u)
                });
            if (i.isFunction(s))
                return this.each(function(f) {
                    var e = i(this);
                    n[0] = s.call(this, f, r ? e.html() : t);
                    e.domManip(n, r, u)
                });
            if (this[0]) {
                if (h = i.buildFragment(n, this, c),
                f = h.fragment,
                o = f.firstChild,
                f.childNodes.length === 1 && (f = o),
                o)
                    for (r = r && i.nodeName(o, "tr"),
                    a = h.cacheable || l - 1; e < l; e++)
                        u.call(r && i.nodeName(this[e], "table") ? pu(this[e], "tbody") : this[e], e === a ? f : i.clone(f, !0, !0));
                f = o = null;
                c.length && i.each(c, function(n, t) {
                    t.src ? i.ajax ? i.ajax({
                        url: t.src,
                        type: "GET",
                        dataType: "script",
                        async: !1,
                        global: !1,
                        throws: !0
                    }) : i.error("no ajax") : i.globalEval((t.text || t.textContent || t.innerHTML || "").replace(ve, ""));
                    t.parentNode && t.parentNode.removeChild(t)
                })
            }
            return this
        }
    });
    i.buildFragment = function(n, u, f) {
        var o, s, h, e = n[0];
        return u = u || r,
        u = !u.nodeType && u[0] || u,
        u = u.ownerDocument || u,
        n.length === 1 && typeof e == "string" && e.length < 512 && u === r && e.charAt(0) === "<" && !le.test(e) && (i.support.checkClone || !tu.test(e)) && (i.support.html5Clone || !dt.test(e)) && (s = !0,
        o = i.fragments[e],
        h = o !== t),
        o || (o = u.createDocumentFragment(),
        i.clean(n, u, o, f),
        s && (i.fragments[e] = h && o)),
        {
            fragment: o,
            cacheable: s
        }
    }
    ;
    i.fragments = {};
    i.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function(n, t) {
        i.fn[n] = function(r) {
            var o, u = 0, s = [], f = i(r), h = f.length, e = this.length === 1 && this[0].parentNode;
            if ((e == null || e && e.nodeType === 11 && e.childNodes.length === 1) && h === 1)
                return f[t](this[0]),
                this;
            for (; u < h; u++)
                o = (u > 0 ? this.clone(!0) : this).get(),
                i(f[u])[t](o),
                s = s.concat(o);
            return this.pushStack(s, n, f.selector)
        }
    });
    i.extend({
        clone: function(n, t, r) {
            var f, o, u, e;
            if (i.support.html5Clone || i.isXMLDoc(n) || !dt.test("<" + n.nodeName + ">") ? e = n.cloneNode(!0) : (gt.innerHTML = n.outerHTML,
            gt.removeChild(e = gt.firstChild)),
            (!i.support.noCloneEvent || !i.support.noCloneChecked) && (n.nodeType === 1 || n.nodeType === 11) && !i.isXMLDoc(n))
                for (hi(n, e),
                f = nt(n),
                o = nt(e),
                u = 0; f[u]; ++u)
                    o[u] && hi(f[u], o[u]);
            if (t && (si(n, e),
            r))
                for (f = nt(n),
                o = nt(e),
                u = 0; f[u]; ++u)
                    si(f[u], o[u]);
            return f = o = null,
            e
        },
        clean: function(n, t, u, f) {
            var h, c, o, p, v, d, s, w, a, b, k, y = t === r && iu, l = [];
            for (t && typeof t.createDocumentFragment != "undefined" || (t = r),
            h = 0; (o = n[h]) != null; h++)
                if (typeof o == "number" && (o += ""),
                o) {
                    if (typeof o == "string")
                        if (he.test(o)) {
                            for (y = y || oi(t),
                            s = t.createElement("div"),
                            y.appendChild(s),
                            o = o.replace(dr, "<$1><\/$2>"),
                            p = (gr.exec(o) || ["", ""])[1].toLowerCase(),
                            v = e[p] || e._default,
                            d = v[0],
                            s.innerHTML = v[1] + o + v[2]; d--; )
                                s = s.lastChild;
                            if (!i.support.tbody)
                                for (w = se.test(o),
                                a = p === "table" && !w ? s.firstChild && s.firstChild.childNodes : v[1] === "<table>" && !w ? s.childNodes : [],
                                c = a.length - 1; c >= 0; --c)
                                    i.nodeName(a[c], "tbody") && !a[c].childNodes.length && a[c].parentNode.removeChild(a[c]);
                            !i.support.leadingWhitespace && kt.test(o) && s.insertBefore(t.createTextNode(kt.exec(o)[0]), s.firstChild);
                            o = s.childNodes;
                            s.parentNode.removeChild(s)
                        } else
                            o = t.createTextNode(o);
                    o.nodeType ? l.push(o) : i.merge(l, o)
                }
            if (s && (o = s = y = null),
            !i.support.appendChecked)
                for (h = 0; (o = l[h]) != null; h++)
                    i.nodeName(o, "input") ? ci(o) : typeof o.getElementsByTagName != "undefined" && i.grep(o.getElementsByTagName("input"), ci);
            if (u)
                for (b = function(n) {
                    if (!n.type || ae.test(n.type))
                        return f ? f.push(n.parentNode ? n.parentNode.removeChild(n) : n) : u.appendChild(n)
                }
                ,
                h = 0; (o = l[h]) != null; h++)
                    i.nodeName(o, "script") && b(o) || (u.appendChild(o),
                    typeof o.getElementsByTagName != "undefined" && (k = i.grep(i.merge([], o.getElementsByTagName("script")), b),
                    l.splice.apply(l, [h + 1, 0].concat(k)),
                    h += k.length));
            return l
        },
        cleanData: function(n, t) {
            for (var f, u, r, e, h = 0, o = i.expando, s = i.cache, c = i.support.deleteExpando, l = i.event.special; (r = n[h]) != null; h++)
                if ((t || i.acceptData(r)) && (u = r[o],
                f = u && s[u],
                f)) {
                    if (f.events)
                        for (e in f.events)
                            l[e] ? i.event.remove(r, e) : i.removeEvent(r, e, f.handle);
                    s[u] && (delete s[u],
                    c ? delete r[o] : r.removeAttribute ? r.removeAttribute(o) : r[o] = null,
                    i.deletedIds.push(u))
                }
        }
    }),
    function() {
        var t, n;
        i.uaMatch = function(n) {
            n = n.toLowerCase();
            var t = /(chrome)[ \/]([\w.]+)/.exec(n) || /(webkit)[ \/]([\w.]+)/.exec(n) || /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(n) || /(msie) ([\w.]+)/.exec(n) || n.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(n) || [];
            return {
                browser: t[1] || "",
                version: t[2] || "0"
            }
        }
        ;
        t = i.uaMatch(rf.userAgent);
        n = {};
        t.browser && (n[t.browser] = !0,
        n.version = t.version);
        n.chrome ? n.webkit = !0 : n.webkit && (n.safari = !0);
        i.browser = n;
        i.sub = function() {
            function n(t, i) {
                return new n.fn.init(t,i)
            }
            i.extend(!0, n, this);
            n.superclass = this;
            n.fn = n.prototype = this();
            n.fn.constructor = n;
            n.sub = this.sub;
            n.fn.init = function(r, u) {
                return u && u instanceof i && !(u instanceof n) && (u = n(u)),
                i.fn.init.call(this, r, u, t)
            }
            ;
            n.fn.init.prototype = n.fn;
            var t = n(r);
            return n
        }
    }();
    var u, y, p, ni = /alpha\([^)]*\)/i, ye = /opacity=([^)]*)/, pe = /^(top|right|bottom|left)$/, we = /^(none|table(?!-c[ea]).+)/, ru = /^margin/, be = new RegExp("^(" + ft + ")(.*)$","i"), ot = new RegExp("^(" + ft + ")(?!px)[a-z%]+$","i"), ke = new RegExp("^([-+])=(" + ft + ")","i"), ti = {
        BODY: "block"
    }, de = {
        position: "absolute",
        visibility: "hidden",
        display: "block"
    }, uu = {
        letterSpacing: 0,
        fontWeight: 400
    }, c = ["Top", "Right", "Bottom", "Left"], fu = ["Webkit", "O", "Moz", "ms"], ge = i.fn.toggle;
    i.fn.extend({
        css: function(n, r) {
            return i.access(this, function(n, r, u) {
                return u !== t ? i.style(n, r, u) : i.css(n, r)
            }, n, r, arguments.length > 1)
        },
        show: function() {
            return ai(this, !0)
        },
        hide: function() {
            return ai(this)
        },
        toggle: function(n, t) {
            var r = typeof n == "boolean";
            return i.isFunction(n) && i.isFunction(t) ? ge.apply(this, arguments) : this.each(function() {
                (r ? n : tt(this)) ? i(this).show() : i(this).hide()
            })
        }
    });
    i.extend({
        cssHooks: {
            opacity: {
                get: function(n, t) {
                    if (t) {
                        var i = u(n, "opacity");
                        return i === "" ? "1" : i
                    }
                }
            }
        },
        cssNumber: {
            fillOpacity: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {
            float: i.support.cssFloat ? "cssFloat" : "styleFloat"
        },
        style: function(n, r, u, f) {
            if (n && n.nodeType !== 3 && n.nodeType !== 8 && n.style) {
                var o, s, e, h = i.camelCase(r), c = n.style;
                if (r = i.cssProps[h] || (i.cssProps[h] = li(c, h)),
                e = i.cssHooks[r] || i.cssHooks[h],
                u === t)
                    return e && "get"in e && (o = e.get(n, !1, f)) !== t ? o : c[r];
                if ((s = typeof u,
                s === "string" && (o = ke.exec(u)) && (u = (o[1] + 1) * o[2] + parseFloat(i.css(n, r)),
                s = "number"),
                u != null && (s !== "number" || !isNaN(u))) && (s !== "number" || i.cssNumber[h] || (u += "px"),
                !e || !("set"in e) || (u = e.set(n, u, f)) !== t))
                    try {
                        c[r] = u
                    } catch (l) {}
            }
        },
        css: function(n, r, f, e) {
            var o, c, s, h = i.camelCase(r);
            return r = i.cssProps[h] || (i.cssProps[h] = li(n.style, h)),
            s = i.cssHooks[r] || i.cssHooks[h],
            s && "get"in s && (o = s.get(n, !0, e)),
            o === t && (o = u(n, r)),
            o === "normal" && r in uu && (o = uu[r]),
            f || e !== t ? (c = parseFloat(o),
            f || i.isNumeric(c) ? c || 0 : o) : o
        },
        swap: function(n, t, i) {
            var u, r, f = {};
            for (r in t)
                f[r] = n.style[r],
                n.style[r] = t[r];
            u = i.call(n);
            for (r in t)
                n.style[r] = f[r];
            return u
        }
    });
    n.getComputedStyle ? u = function(t, r) {
        var f, o, s, h, e = n.getComputedStyle(t, null), u = t.style;
        return e && (f = e.getPropertyValue(r) || e[r],
        f === "" && !i.contains(t.ownerDocument, t) && (f = i.style(t, r)),
        ot.test(f) && ru.test(r) && (o = u.width,
        s = u.minWidth,
        h = u.maxWidth,
        u.minWidth = u.maxWidth = u.width = f,
        f = e.width,
        u.width = o,
        u.minWidth = s,
        u.maxWidth = h)),
        f
    }
    : r.documentElement.currentStyle && (u = function(n, t) {
        var f, u, i = n.currentStyle && n.currentStyle[t], r = n.style;
        return i == null && r && r[t] && (i = r[t]),
        ot.test(i) && !pe.test(t) && (f = r.left,
        u = n.runtimeStyle && n.runtimeStyle.left,
        u && (n.runtimeStyle.left = n.currentStyle.left),
        r.left = t === "fontSize" ? "1em" : i,
        i = r.pixelLeft + "px",
        r.left = f,
        u && (n.runtimeStyle.left = u)),
        i === "" ? "auto" : i
    }
    );
    i.each(["height", "width"], function(n, t) {
        i.cssHooks[t] = {
            get: function(n, r, f) {
                if (r)
                    return n.offsetWidth === 0 && we.test(u(n, "display")) ? i.swap(n, de, function() {
                        return pi(n, t, f)
                    }) : pi(n, t, f)
            },
            set: function(n, r, u) {
                return vi(n, r, u ? yi(n, t, u, i.support.boxSizing && i.css(n, "boxSizing") === "border-box") : 0)
            }
        }
    });
    i.support.opacity || (i.cssHooks.opacity = {
        get: function(n, t) {
            return ye.test((t && n.currentStyle ? n.currentStyle.filter : n.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "" : t ? "1" : ""
        },
        set: function(n, t) {
            var r = n.style
              , u = n.currentStyle
              , e = i.isNumeric(t) ? "alpha(opacity=" + t * 100 + ")" : ""
              , f = u && u.filter || r.filter || "";
            (r.zoom = 1,
            t >= 1 && i.trim(f.replace(ni, "")) === "" && r.removeAttribute && (r.removeAttribute("filter"),
            u && !u.filter)) || (r.filter = ni.test(f) ? f.replace(ni, e) : f + " " + e)
        }
    });
    i(function() {
        i.support.reliableMarginRight || (i.cssHooks.marginRight = {
            get: function(n, t) {
                return i.swap(n, {
                    display: "inline-block"
                }, function() {
                    if (t)
                        return u(n, "marginRight")
                })
            }
        });
        !i.support.pixelPosition && i.fn.position && i.each(["top", "left"], function(n, t) {
            i.cssHooks[t] = {
                get: function(n, r) {
                    if (r) {
                        var f = u(n, t);
                        return ot.test(f) ? i(n).position()[t] + "px" : f
                    }
                }
            }
        })
    });
    i.expr && i.expr.filters && (i.expr.filters.hidden = function(n) {
        return n.offsetWidth === 0 && n.offsetHeight === 0 || !i.support.reliableHiddenOffsets && (n.style && n.style.display || u(n, "display")) === "none"
    }
    ,
    i.expr.filters.visible = function(n) {
        return !i.expr.filters.hidden(n)
    }
    );
    i.each({
        margin: "",
        padding: "",
        border: "Width"
    }, function(n, t) {
        i.cssHooks[n + t] = {
            expand: function(i) {
                for (var u = typeof i == "string" ? i.split(" ") : [i], f = {}, r = 0; r < 4; r++)
                    f[n + c[r] + t] = u[r] || u[r - 2] || u[0];
                return f
            }
        };
        ru.test(n) || (i.cssHooks[n + t].set = vi)
    });
    var no = /%20/g
      , to = /\[\]$/
      , eu = /\r?\n/g
      , io = /^(?:color|date|datetime|datetime-local|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i
      , ro = /^(?:select|textarea)/i;
    i.fn.extend({
        serialize: function() {
            return i.param(this.serializeArray())
        },
        serializeArray: function() {
            return this.map(function() {
                return this.elements ? i.makeArray(this.elements) : this
            }).filter(function() {
                return this.name && !this.disabled && (this.checked || ro.test(this.nodeName) || io.test(this.type))
            }).map(function(n, t) {
                var r = i(this).val();
                return r == null ? null : i.isArray(r) ? i.map(r, function(n) {
                    return {
                        name: t.name,
                        value: n.replace(eu, "\r\n")
                    }
                }) : {
                    name: t.name,
                    value: r.replace(eu, "\r\n")
                }
            }).get()
        }
    });
    i.param = function(n, r) {
        var u, f = [], e = function(n, t) {
            t = i.isFunction(t) ? t() : t == null ? "" : t;
            f[f.length] = encodeURIComponent(n) + "=" + encodeURIComponent(t)
        };
        if (r === t && (r = i.ajaxSettings && i.ajaxSettings.traditional),
        i.isArray(n) || n.jquery && !i.isPlainObject(n))
            i.each(n, function() {
                e(this.name, this.value)
            });
        else
            for (u in n)
                vt(u, n[u], r, e);
        return f.join("&").replace(no, "+")
    }
    ;
    var l, a, uo = /#.*$/, fo = /^(.*?):[ \t]*([^\r\n]*)\r?$/mg, eo = /^(?:GET|HEAD)$/, oo = /^\/\//, ou = /\?/, so = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, ho = /([?&])_=[^&]*/, su = /^([\w\+\.\-]+:)(?:\/\/([^\/?#:]*)(?::(\d+)|)|)/, hu = i.fn.load, ii = {}, cu = {}, lu = ["*/"] + ["*"];
    try {
        a = tf.href
    } catch (po) {
        a = r.createElement("a");
        a.href = "";
        a = a.href
    }
    l = su.exec(a.toLowerCase()) || [];
    i.fn.load = function(n, r, u) {
        if (typeof n != "string" && hu)
            return hu.apply(this, arguments);
        if (!this.length)
            return this;
        var f, o, s, h = this, e = n.indexOf(" ");
        return e >= 0 && (f = n.slice(e, n.length),
        n = n.slice(0, e)),
        i.isFunction(r) ? (u = r,
        r = t) : r && typeof r == "object" && (o = "POST"),
        i.ajax({
            url: n,
            type: o,
            dataType: "html",
            data: r,
            complete: function(n, t) {
                u && h.each(u, s || [n.responseText, t, n])
            }
        }).done(function(n) {
            s = arguments;
            h.html(f ? i("<div>").append(n.replace(so, "")).find(f) : n)
        }),
        this
    }
    ;
    i.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "), function(n, t) {
        i.fn[t] = function(n) {
            return this.on(t, n)
        }
    });
    i.each(["get", "post"], function(n, r) {
        i[r] = function(n, u, f, e) {
            return i.isFunction(u) && (e = e || f,
            f = u,
            u = t),
            i.ajax({
                type: r,
                url: n,
                data: u,
                success: f,
                dataType: e
            })
        }
    });
    i.extend({
        getScript: function(n, r) {
            return i.get(n, t, r, "script")
        },
        getJSON: function(n, t, r) {
            return i.get(n, t, r, "json")
        },
        ajaxSetup: function(n, t) {
            return t ? ki(n, i.ajaxSettings) : (t = n,
            n = i.ajaxSettings),
            ki(n, t),
            n
        },
        ajaxSettings: {
            url: a,
            isLocal: /^(?:about|app|app\-storage|.+\-extension|file|res|widget):$/.test(l[1]),
            global: !0,
            type: "GET",
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            processData: !0,
            async: !0,
            accepts: {
                xml: "application/xml, text/xml",
                html: "text/html",
                text: "text/plain",
                json: "application/json, text/javascript",
                "*": lu
            },
            contents: {
                xml: /xml/,
                html: /html/,
                json: /json/
            },
            responseFields: {
                xml: "responseXML",
                text: "responseText"
            },
            converters: {
                "* text": n.String,
                "text html": !0,
                "text json": i.parseJSON,
                "text xml": i.parseXML
            },
            flatOptions: {
                context: !0,
                url: !0
            }
        },
        ajaxPrefilter: bi(ii),
        ajaxTransport: bi(cu),
        ajax: function(n, r) {
            function p(n, r, h, l) {
                var a, tt, w, it, p, v = r;
                e !== 2 && (e = 2,
                d && clearTimeout(d),
                c = t,
                k = l || "",
                f.readyState = n > 0 ? 4 : 0,
                h && (it = wu(u, f, h)),
                n >= 200 && n < 300 || n === 304 ? (u.ifModified && (p = f.getResponseHeader("Last-Modified"),
                p && (i.lastModified[o] = p),
                p = f.getResponseHeader("Etag"),
                p && (i.etag[o] = p)),
                n === 304 ? (v = "notmodified",
                a = !0) : (a = bu(u, it),
                v = a.state,
                tt = a.data,
                w = a.error,
                a = !w)) : (w = v,
                (!v || n) && (v = "error",
                n < 0 && (n = 0))),
                f.status = n,
                f.statusText = (r || v) + "",
                a ? nt.resolveWith(s, [tt, v, f]) : nt.rejectWith(s, [f, v, w]),
                f.statusCode(b),
                b = t,
                y && g.trigger("ajax" + (a ? "Success" : "Error"), [f, u, a ? tt : w]),
                ut.fireWith(s, [f, v]),
                y && (g.trigger("ajaxComplete", [f, u]),
                --i.active || i.event.trigger("ajaxStop")))
            }
            var tt, rt;
            typeof n == "object" && (r = n,
            n = t);
            r = r || {};
            var o, k, w, c, d, a, y, v, u = i.ajaxSetup({}, r), s = u.context || u, g = s !== u && (s.nodeType || s instanceof i) ? i(s) : i.event, nt = i.Deferred(), ut = i.Callbacks("once memory"), b = u.statusCode || {}, ft = {}, et = {}, e = 0, ot = "canceled", f = {
                readyState: 0,
                setRequestHeader: function(n, t) {
                    if (!e) {
                        var i = n.toLowerCase();
                        n = et[i] = et[i] || n;
                        ft[n] = t
                    }
                    return this
                },
                getAllResponseHeaders: function() {
                    return e === 2 ? k : null
                },
                getResponseHeader: function(n) {
                    var i;
                    if (e === 2) {
                        if (!w)
                            for (w = {}; i = fo.exec(k); )
                                w[i[1].toLowerCase()] = i[2];
                        i = w[n.toLowerCase()]
                    }
                    return i === t ? null : i
                },
                overrideMimeType: function(n) {
                    return e || (u.mimeType = n),
                    this
                },
                abort: function(n) {
                    return n = n || ot,
                    c && c.abort(n),
                    p(0, n),
                    this
                }
            };
            if (nt.promise(f),
            f.success = f.done,
            f.error = f.fail,
            f.complete = ut.add,
            f.statusCode = function(n) {
                if (n) {
                    var t;
                    if (e < 2)
                        for (t in n)
                            b[t] = [b[t], n[t]];
                    else
                        t = n[f.status],
                        f.always(t)
                }
                return this
            }
            ,
            u.url = ((n || u.url) + "").replace(uo, "").replace(oo, l[1] + "//"),
            u.dataTypes = i.trim(u.dataType || "*").toLowerCase().split(h),
            u.crossDomain == null && (a = su.exec(u.url.toLowerCase()),
            u.crossDomain = !(!a || a[1] === l[1] && a[2] === l[2] && (a[3] || (a[1] === "http:" ? 80 : 443)) == (l[3] || (l[1] === "http:" ? 80 : 443)))),
            u.data && u.processData && typeof u.data != "string" && (u.data = i.param(u.data, u.traditional)),
            it(ii, u, r, f),
            e === 2)
                return f;
            y = u.global;
            u.type = u.type.toUpperCase();
            u.hasContent = !eo.test(u.type);
            y && i.active++ == 0 && i.event.trigger("ajaxStart");
            u.hasContent || (u.data && (u.url += (ou.test(u.url) ? "&" : "?") + u.data,
            delete u.data),
            o = u.url,
            u.cache === !1 && (tt = i.now(),
            rt = u.url.replace(ho, "$1_=" + tt),
            u.url = rt + (rt === u.url ? (ou.test(u.url) ? "&" : "?") + "_=" + tt : "")));
            (u.data && u.hasContent && u.contentType !== !1 || r.contentType) && f.setRequestHeader("Content-Type", u.contentType);
            u.ifModified && (o = o || u.url,
            i.lastModified[o] && f.setRequestHeader("If-Modified-Since", i.lastModified[o]),
            i.etag[o] && f.setRequestHeader("If-None-Match", i.etag[o]));
            f.setRequestHeader("Accept", u.dataTypes[0] && u.accepts[u.dataTypes[0]] ? u.accepts[u.dataTypes[0]] + (u.dataTypes[0] !== "*" ? ", " + lu + "; q=0.01" : "") : u.accepts["*"]);
            for (v in u.headers)
                f.setRequestHeader(v, u.headers[v]);
            if (!u.beforeSend || u.beforeSend.call(s, f, u) !== !1 && e !== 2) {
                ot = "abort";
                for (v in {
                    success: 1,
                    error: 1,
                    complete: 1
                })
                    f[v](u[v]);
                if (c = it(cu, u, r, f),
                c) {
                    f.readyState = 1;
                    y && g.trigger("ajaxSend", [f, u]);
                    u.async && u.timeout > 0 && (d = setTimeout(function() {
                        f.abort("timeout")
                    }, u.timeout));
                    try {
                        e = 1;
                        c.send(ft, p)
                    } catch (st) {
                        if (!(e < 2))
                            throw st;
                        p(-1, st)
                    }
                } else
                    p(-1, "No Transport");
                return f
            }
            return f.abort()
        },
        active: 0,
        lastModified: {},
        etag: {}
    });
    var au = []
      , co = /\?/
      , st = /(=)\?(?=&|$)|\?\?/
      , lo = i.now();
    i.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function() {
            var n = au.pop() || i.expando + "_" + lo++;
            return this[n] = !0,
            n
        }
    });
    i.ajaxPrefilter("json jsonp", function(r, u, f) {
        var e, s, o, h = r.data, c = r.url, l = r.jsonp !== !1, a = l && st.test(c), v = l && !a && typeof h == "string" && !(r.contentType || "").indexOf("application/x-www-form-urlencoded") && st.test(h);
        if (r.dataTypes[0] === "jsonp" || a || v)
            return e = r.jsonpCallback = i.isFunction(r.jsonpCallback) ? r.jsonpCallback() : r.jsonpCallback,
            s = n[e],
            a ? r.url = c.replace(st, "$1" + e) : v ? r.data = h.replace(st, "$1" + e) : l && (r.url += (co.test(c) ? "&" : "?") + r.jsonp + "=" + e),
            r.converters["script json"] = function() {
                return o || i.error(e + " was not called"),
                o[0]
            }
            ,
            r.dataTypes[0] = "json",
            n[e] = function() {
                o = arguments
            }
            ,
            f.always(function() {
                n[e] = s;
                r[e] && (r.jsonpCallback = u.jsonpCallback,
                au.push(e));
                o && i.isFunction(s) && s(o[0]);
                o = s = t
            }),
            "script"
    });
    i.ajaxSetup({
        accepts: {
            script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        contents: {
            script: /javascript|ecmascript/
        },
        converters: {
            "text script": function(n) {
                return i.globalEval(n),
                n
            }
        }
    });
    i.ajaxPrefilter("script", function(n) {
        n.cache === t && (n.cache = !1);
        n.crossDomain && (n.type = "GET",
        n.global = !1)
    });
    i.ajaxTransport("script", function(n) {
        if (n.crossDomain) {
            var i, u = r.head || r.getElementsByTagName("head")[0] || r.documentElement;
            return {
                send: function(f, e) {
                    i = r.createElement("script");
                    i.async = "async";
                    n.scriptCharset && (i.charset = n.scriptCharset);
                    i.src = n.url;
                    i.onload = i.onreadystatechange = function(n, r) {
                        (r || !i.readyState || /loaded|complete/.test(i.readyState)) && (i.onload = i.onreadystatechange = null,
                        u && i.parentNode && u.removeChild(i),
                        i = t,
                        r || e(200, "success"))
                    }
                    ;
                    u.insertBefore(i, u.firstChild)
                },
                abort: function() {
                    i && i.onload(0, 1)
                }
            }
        }
    });
    ht = n.ActiveXObject ? function() {
        for (var n in w)
            w[n](0, 1)
    }
    : !1;
    vu = 0;
    i.ajaxSettings.xhr = n.ActiveXObject ? function() {
        return !this.isLocal && di() || ku()
    }
    : di,
    function(n) {
        i.extend(i.support, {
            ajax: !!n,
            cors: !!n && "withCredentials"in n
        })
    }(i.ajaxSettings.xhr());
    i.support.ajax && i.ajaxTransport(function(r) {
        if (!r.crossDomain || i.support.cors) {
            var u;
            return {
                send: function(f, e) {
                    var h, s, o = r.xhr();
                    if (r.username ? o.open(r.type, r.url, r.async, r.username, r.password) : o.open(r.type, r.url, r.async),
                    r.xhrFields)
                        for (s in r.xhrFields)
                            o[s] = r.xhrFields[s];
                    r.mimeType && o.overrideMimeType && o.overrideMimeType(r.mimeType);
                    r.crossDomain || f["X-Requested-With"] || (f["X-Requested-With"] = "XMLHttpRequest");
                    try {
                        for (s in f)
                            o.setRequestHeader(s, f[s])
                    } catch (c) {}
                    o.send(r.hasContent && r.data || null);
                    u = function(n, f) {
                        var s, a, v, c, l;
                        try {
                            if (u && (f || o.readyState === 4))
                                if (u = t,
                                h && (o.onreadystatechange = i.noop,
                                ht && delete w[h]),
                                f)
                                    o.readyState !== 4 && o.abort();
                                else {
                                    s = o.status;
                                    v = o.getAllResponseHeaders();
                                    c = {};
                                    l = o.responseXML;
                                    l && l.documentElement && (c.xml = l);
                                    try {
                                        c.text = o.responseText
                                    } catch (p) {}
                                    try {
                                        a = o.statusText
                                    } catch (p) {
                                        a = ""
                                    }
                                    !s && r.isLocal && !r.crossDomain ? s = c.text ? 200 : 404 : s === 1223 && (s = 204)
                                }
                        } catch (y) {
                            f || e(-1, y)
                        }
                        c && e(s, a, c, v)
                    }
                    ;
                    r.async ? o.readyState === 4 ? setTimeout(u, 0) : (h = ++vu,
                    ht && (w || (w = {},
                    i(n).unload(ht)),
                    w[h] = u),
                    o.onreadystatechange = u) : u()
                },
                abort: function() {
                    u && u(0, 1)
                }
            }
        }
    });
    var b, ct, ao = /^(?:toggle|show|hide)$/, vo = new RegExp("^(?:([-+])=|)(" + ft + ")([a-z%]*)$","i"), yo = /queueHooks$/, lt = [nf], d = {
        "*": [function(n, t) {
            var o, s, r = this.createTween(n, t), e = vo.exec(t), h = r.cur(), u = +h || 0, f = 1, c = 20;
            if (e) {
                if (o = +e[2],
                s = e[3] || (i.cssNumber[n] ? "" : "px"),
                s !== "px" && u) {
                    u = i.css(r.elem, n, !0) || o || 1;
                    do
                        f = f || ".5",
                        u /= f,
                        i.style(r.elem, n, u + s);
                    while (f !== (f = r.cur() / h) && f !== 1 && --c)
                }
                r.unit = s;
                r.start = u;
                r.end = e[1] ? u + (e[1] + 1) * o : o
            }
            return r
        }
        ]
    };
    i.Animation = i.extend(nr, {
        tweener: function(n, t) {
            i.isFunction(n) ? (t = n,
            n = ["*"]) : n = n.split(" ");
            for (var r, u = 0, f = n.length; u < f; u++)
                r = n[u],
                d[r] = d[r] || [],
                d[r].unshift(t)
        },
        prefilter: function(n, t) {
            t ? lt.unshift(n) : lt.push(n)
        }
    });
    i.Tween = f;
    f.prototype = {
        constructor: f,
        init: function(n, t, r, u, f, e) {
            this.elem = n;
            this.prop = r;
            this.easing = f || "swing";
            this.options = t;
            this.start = this.now = this.cur();
            this.end = u;
            this.unit = e || (i.cssNumber[r] ? "" : "px")
        },
        cur: function() {
            var n = f.propHooks[this.prop];
            return n && n.get ? n.get(this) : f.propHooks._default.get(this)
        },
        run: function(n) {
            var t, r = f.propHooks[this.prop];
            return this.pos = this.options.duration ? t = i.easing[this.easing](n, this.options.duration * n, 0, 1, this.options.duration) : t = n,
            this.now = (this.end - this.start) * t + this.start,
            this.options.step && this.options.step.call(this.elem, this.now, this),
            r && r.set ? r.set(this) : f.propHooks._default.set(this),
            this
        }
    };
    f.prototype.init.prototype = f.prototype;
    f.propHooks = {
        _default: {
            get: function(n) {
                var t;
                return n.elem[n.prop] == null || !!n.elem.style && n.elem.style[n.prop] != null ? (t = i.css(n.elem, n.prop, !1, ""),
                !t || t === "auto" ? 0 : t) : n.elem[n.prop]
            },
            set: function(n) {
                i.fx.step[n.prop] ? i.fx.step[n.prop](n) : n.elem.style && (n.elem.style[i.cssProps[n.prop]] != null || i.cssHooks[n.prop]) ? i.style(n.elem, n.prop, n.now + n.unit) : n.elem[n.prop] = n.now
            }
        }
    };
    f.propHooks.scrollTop = f.propHooks.scrollLeft = {
        set: function(n) {
            n.elem.nodeType && n.elem.parentNode && (n.elem[n.prop] = n.now)
        }
    };
    i.each(["toggle", "show", "hide"], function(n, t) {
        var r = i.fn[t];
        i.fn[t] = function(u, f, e) {
            return u == null || typeof u == "boolean" || !n && i.isFunction(u) && i.isFunction(f) ? r.apply(this, arguments) : this.animate(rt(t, !0), u, f, e)
        }
    });
    i.fn.extend({
        fadeTo: function(n, t, i, r) {
            return this.filter(tt).css("opacity", 0).show().end().animate({
                opacity: t
            }, n, i, r)
        },
        animate: function(n, t, r, u) {
            var e = i.isEmptyObject(n)
              , f = i.speed(t, r, u)
              , o = function() {
                var t = nr(this, i.extend({}, n), f);
                e && t.stop(!0)
            };
            return e || f.queue === !1 ? this.each(o) : this.queue(f.queue, o)
        },
        stop: function(n, r, u) {
            var f = function(n) {
                var t = n.stop;
                delete n.stop;
                t(u)
            };
            return typeof n != "string" && (u = r,
            r = n,
            n = t),
            r && n !== !1 && this.queue(n || "fx", []),
            this.each(function() {
                var o = !0
                  , t = n != null && n + "queueHooks"
                  , e = i.timers
                  , r = i._data(this);
                if (t)
                    r[t] && r[t].stop && f(r[t]);
                else
                    for (t in r)
                        r[t] && r[t].stop && yo.test(t) && f(r[t]);
                for (t = e.length; t--; )
                    e[t].elem === this && (n == null || e[t].queue === n) && (e[t].anim.stop(u),
                    o = !1,
                    e.splice(t, 1));
                (o || !u) && i.dequeue(this, n)
            })
        }
    });
    i.each({
        slideDown: rt("show"),
        slideUp: rt("hide"),
        slideToggle: rt("toggle"),
        fadeIn: {
            opacity: "show"
        },
        fadeOut: {
            opacity: "hide"
        },
        fadeToggle: {
            opacity: "toggle"
        }
    }, function(n, t) {
        i.fn[n] = function(n, i, r) {
            return this.animate(t, n, i, r)
        }
    });
    i.speed = function(n, t, r) {
        var u = n && typeof n == "object" ? i.extend({}, n) : {
            complete: r || !r && t || i.isFunction(n) && n,
            duration: n,
            easing: r && t || t && !i.isFunction(t) && t
        };
        return u.duration = i.fx.off ? 0 : typeof u.duration == "number" ? u.duration : u.duration in i.fx.speeds ? i.fx.speeds[u.duration] : i.fx.speeds._default,
        (u.queue == null || u.queue === !0) && (u.queue = "fx"),
        u.old = u.complete,
        u.complete = function() {
            i.isFunction(u.old) && u.old.call(this);
            u.queue && i.dequeue(this, u.queue)
        }
        ,
        u
    }
    ;
    i.easing = {
        linear: function(n) {
            return n
        },
        swing: function(n) {
            return .5 - Math.cos(n * Math.PI) / 2
        }
    };
    i.timers = [];
    i.fx = f.prototype.init;
    i.fx.tick = function() {
        var u, n = i.timers, r = 0;
        for (b = i.now(); r < n.length; r++)
            u = n[r],
            u() || n[r] !== u || n.splice(r--, 1);
        n.length || i.fx.stop();
        b = t
    }
    ;
    i.fx.timer = function(n) {
        n() && i.timers.push(n) && !ct && (ct = setInterval(i.fx.tick, i.fx.interval))
    }
    ;
    i.fx.interval = 13;
    i.fx.stop = function() {
        clearInterval(ct);
        ct = null
    }
    ;
    i.fx.speeds = {
        slow: 600,
        fast: 200,
        _default: 400
    };
    i.fx.step = {};
    i.expr && i.expr.filters && (i.expr.filters.animated = function(n) {
        return i.grep(i.timers, function(t) {
            return n === t.elem
        }).length
    }
    );
    ri = /^(?:body|html)$/i;
    i.fn.offset = function(n) {
        if (arguments.length)
            return n === t ? this : this.each(function(t) {
                i.offset.setOffset(this, n, t)
            });
        var u, o, s, h, c, l, a, f = {
            top: 0,
            left: 0
        }, r = this[0], e = r && r.ownerDocument;
        if (e)
            return (o = e.body) === r ? i.offset.bodyOffset(r) : (u = e.documentElement,
            i.contains(u, r) ? (typeof r.getBoundingClientRect != "undefined" && (f = r.getBoundingClientRect()),
            s = tr(e),
            h = u.clientTop || o.clientTop || 0,
            c = u.clientLeft || o.clientLeft || 0,
            l = s.pageYOffset || u.scrollTop,
            a = s.pageXOffset || u.scrollLeft,
            {
                top: f.top + l - h,
                left: f.left + a - c
            }) : f)
    }
    ;
    i.offset = {
        bodyOffset: function(n) {
            var t = n.offsetTop
              , r = n.offsetLeft;
            return i.support.doesNotIncludeMarginInBodyOffset && (t += parseFloat(i.css(n, "marginTop")) || 0,
            r += parseFloat(i.css(n, "marginLeft")) || 0),
            {
                top: t,
                left: r
            }
        },
        setOffset: function(n, t, r) {
            var f = i.css(n, "position");
            f === "static" && (n.style.position = "relative");
            var e = i(n), o = e.offset(), l = i.css(n, "top"), a = i.css(n, "left"), v = (f === "absolute" || f === "fixed") && i.inArray("auto", [l, a]) > -1, u = {}, s = {}, h, c;
            v ? (s = e.position(),
            h = s.top,
            c = s.left) : (h = parseFloat(l) || 0,
            c = parseFloat(a) || 0);
            i.isFunction(t) && (t = t.call(n, r, o));
            t.top != null && (u.top = t.top - o.top + h);
            t.left != null && (u.left = t.left - o.left + c);
            "using"in t ? t.using.call(n, u) : e.css(u)
        }
    };
    i.fn.extend({
        position: function() {
            if (this[0]) {
                var u = this[0]
                  , n = this.offsetParent()
                  , t = this.offset()
                  , r = ri.test(n[0].nodeName) ? {
                    top: 0,
                    left: 0
                } : n.offset();
                return t.top -= parseFloat(i.css(u, "marginTop")) || 0,
                t.left -= parseFloat(i.css(u, "marginLeft")) || 0,
                r.top += parseFloat(i.css(n[0], "borderTopWidth")) || 0,
                r.left += parseFloat(i.css(n[0], "borderLeftWidth")) || 0,
                {
                    top: t.top - r.top,
                    left: t.left - r.left
                }
            }
        },
        offsetParent: function() {
            return this.map(function() {
                for (var n = this.offsetParent || r.body; n && !ri.test(n.nodeName) && i.css(n, "position") === "static"; )
                    n = n.offsetParent;
                return n || r.body
            })
        }
    });
    i.each({
        scrollLeft: "pageXOffset",
        scrollTop: "pageYOffset"
    }, function(n, r) {
        var u = /Y/.test(r);
        i.fn[n] = function(f) {
            return i.access(this, function(n, f, e) {
                var o = tr(n);
                if (e === t)
                    return o ? r in o ? o[r] : o.document.documentElement[f] : n[f];
                o ? o.scrollTo(u ? i(o).scrollLeft() : e, u ? e : i(o).scrollTop()) : n[f] = e
            }, n, f, arguments.length, null)
        }
    });
    i.each({
        Height: "height",
        Width: "width"
    }, function(n, r) {
        i.each({
            padding: "inner" + n,
            content: r,
            "": "outer" + n
        }, function(u, f) {
            i.fn[f] = function(f, e) {
                var o = arguments.length && (u || typeof f != "boolean")
                  , s = u || (f === !0 || e === !0 ? "margin" : "border");
                return i.access(this, function(r, u, f) {
                    var e;
                    return i.isWindow(r) ? r.document.documentElement["client" + n] : r.nodeType === 9 ? (e = r.documentElement,
                    Math.max(r.body["scroll" + n], e["scroll" + n], r.body["offset" + n], e["offset" + n], e["client" + n])) : f === t ? i.css(r, u, f, s) : i.style(r, u, f, s)
                }, r, o ? f : t, o, null)
            }
        })
    });
    n.jQuery = n.$ = i;
    typeof define == "function" && define.amd && define.amd.jQuery && define("jquery", [], function() {
        return i
    })
}
)(window);
lastSuggest = (new Date).getTime();
$(window).load(function() {
    $(".colfoot li.showmore").click(function() {
        $(this).remove();
        $(".colfoot li.hidden").removeClass("hidden")
    });
    getScriptChatTgdd();
    checkCmtParam();
    getJsRateShip()
});
$(window).scroll(function() {
    $(this).scrollTop() > 300 ? $("#back-top").fadeIn() : $("#back-top").fadeOut()
});
$("#back-top a").click(function() {
    return $("body,html").animate({
        scrollTop: 0
    }, 800),
    !1
});
gl_fLoadChat = !1,
function(n) {
    typeof define == "function" && define.amd ? define(["jquery"], n) : n(jQuery)
}(function(n) {
    n.extend(n.fn, {
        validate: function(t) {
            if (!this.length) {
                t && t.debug && window.console && console.warn("Nothing selected, can't validate, returning nothing.");
                return
            }
            var i = n.data(this[0], "validator");
            if (i)
                return i;
            if (this.attr("novalidate", "novalidate"),
            i = new n.validator(t,this[0]),
            n.data(this[0], "validator", i),
            i.settings.onsubmit) {
                this.on("click.validate", ":submit", function(t) {
                    i.settings.submitHandler && (i.submitButton = t.target);
                    n(this).hasClass("cancel") && (i.cancelSubmit = !0);
                    n(this).attr("formnovalidate") !== undefined && (i.cancelSubmit = !0)
                });
                this.on("submit.validate", function(t) {
                    function r() {
                        var u, r;
                        return i.settings.submitHandler ? (i.submitButton && (u = n("<input type='hidden'/>").attr("name", i.submitButton.name).val(n(i.submitButton).val()).appendTo(i.currentForm)),
                        r = i.settings.submitHandler.call(i, i.currentForm, t),
                        i.submitButton && u.remove(),
                        r !== undefined) ? r : !1 : !0
                    }
                    return (i.settings.debug && t.preventDefault(),
                    i.cancelSubmit) ? (i.cancelSubmit = !1,
                    r()) : i.form() ? i.pendingRequest ? (i.formSubmitted = !0,
                    !1) : r() : (i.focusInvalid(),
                    !1)
                })
            }
            return i
        },
        valid: function() {
            var t, i, r;
            return n(this[0]).is("form") ? t = this.validate().form() : (r = [],
            t = !0,
            i = n(this[0].form).validate(),
            this.each(function() {
                t = i.element(this) && t;
                r = r.concat(i.errorList)
            }),
            i.errorList = r),
            t
        },
        rules: function(t, i) {
            var r = this[0], e, s, f, u, o, h;
            if (t) {
                e = n.data(r.form, "validator").settings;
                s = e.rules;
                f = n.validator.staticRules(r);
                switch (t) {
                case "add":
                    n.extend(f, n.validator.normalizeRule(i));
                    delete f.messages;
                    s[r.name] = f;
                    i.messages && (e.messages[r.name] = n.extend(e.messages[r.name], i.messages));
                    break;
                case "remove":
                    return i ? (h = {},
                    n.each(i.split(/\s/), function(t, i) {
                        h[i] = f[i];
                        delete f[i];
                        i === "required" && n(r).removeAttr("aria-required")
                    }),
                    h) : (delete s[r.name],
                    f)
                }
            }
            return u = n.validator.normalizeRules(n.extend({}, n.validator.classRules(r), n.validator.attributeRules(r), n.validator.dataRules(r), n.validator.staticRules(r)), r),
            u.required && (o = u.required,
            delete u.required,
            u = n.extend({
                required: o
            }, u),
            n(r).attr("aria-required", "true")),
            u.remote && (o = u.remote,
            delete u.remote,
            u = n.extend(u, {
                remote: o
            })),
            u
        }
    });
    n.extend(n.expr[":"], {
        blank: function(t) {
            return !n.trim("" + n(t).val())
        },
        filled: function(t) {
            return !!n.trim("" + n(t).val())
        },
        unchecked: function(t) {
            return !n(t).prop("checked")
        }
    });
    n.validator = function(t, i) {
        this.settings = n.extend(!0, {}, n.validator.defaults, t);
        this.currentForm = i;
        this.init()
    }
    ;
    n.validator.format = function(t, i) {
        return arguments.length === 1 ? function() {
            var i = n.makeArray(arguments);
            return i.unshift(t),
            n.validator.format.apply(this, i)
        }
        : (arguments.length > 2 && i.constructor !== Array && (i = n.makeArray(arguments).slice(1)),
        i.constructor !== Array && (i = [i]),
        n.each(i, function(n, i) {
            t = t.replace(new RegExp("\\{" + n + "\\}","g"), function() {
                return i
            })
        }),
        t)
    }
    ;
    n.extend(n.validator, {
        defaults: {
            messages: {},
            groups: {},
            rules: {},
            errorClass: "error",
            validClass: "valid",
            errorElement: "label",
            focusCleanup: !1,
            focusInvalid: !0,
            errorContainer: n([]),
            errorLabelContainer: n([]),
            onsubmit: !0,
            ignore: ":hidden",
            ignoreTitle: !1,
            onfocusin: function(n) {
                this.lastActive = n;
                this.settings.focusCleanup && (this.settings.unhighlight && this.settings.unhighlight.call(this, n, this.settings.errorClass, this.settings.validClass),
                this.hideThese(this.errorsFor(n)))
            },
            onfocusout: function(n) {
                !this.checkable(n) && (n.name in this.submitted || !this.optional(n)) && this.element(n)
            },
            onkeyup: function(t, i) {
                (i.which !== 9 || this.elementValue(t) !== "") && n.inArray(i.keyCode, [16, 17, 18, 20, 35, 36, 37, 38, 39, 40, 45, 144, 225]) === -1 && (t.name in this.submitted || t === this.lastElement) && this.element(t)
            },
            onclick: function(n) {
                n.name in this.submitted ? this.element(n) : n.parentNode.name in this.submitted && this.element(n.parentNode)
            },
            highlight: function(t, i, r) {
                t.type === "radio" ? this.findByName(t.name).addClass(i).removeClass(r) : n(t).addClass(i).removeClass(r)
            },
            unhighlight: function(t, i, r) {
                t.type === "radio" ? this.findByName(t.name).removeClass(i).addClass(r) : n(t).removeClass(i).addClass(r)
            }
        },
        setDefaults: function(t) {
            n.extend(n.validator.defaults, t)
        },
        messages: {
            required: "This field is required.",
            remote: "Please fix this field.",
            email: "Please enter a valid email address.",
            url: "Please enter a valid URL.",
            date: "Please enter a valid date.",
            dateISO: "Please enter a valid date ( ISO ).",
            number: "Please enter a valid number.",
            digits: "Please enter only digits.",
            creditcard: "Please enter a valid credit card number.",
            equalTo: "Please enter the same value again.",
            maxlength: n.validator.format("Please enter no more than {0} characters."),
            minlength: n.validator.format("Please enter at least {0} characters."),
            rangelength: n.validator.format("Please enter a value between {0} and {1} characters long."),
            range: n.validator.format("Please enter a value between {0} and {1}."),
            max: n.validator.format("Please enter a value less than or equal to {0}."),
            min: n.validator.format("Please enter a value greater than or equal to {0}.")
        },
        autoCreateRanges: !1,
        prototype: {
            init: function() {
                function i(t) {
                    var r = n.data(this.form, "validator")
                      , u = "on" + t.type.replace(/^validate/, "")
                      , i = r.settings;
                    i[u] && !n(this).is(i.ignore) && i[u].call(r, this, t)
                }
                this.labelContainer = n(this.settings.errorLabelContainer);
                this.errorContext = this.labelContainer.length && this.labelContainer || n(this.currentForm);
                this.containers = n(this.settings.errorContainer).add(this.settings.errorLabelContainer);
                this.submitted = {};
                this.valueCache = {};
                this.pendingRequest = 0;
                this.pending = {};
                this.invalid = {};
                this.reset();
                var r = this.groups = {}, t;
                n.each(this.settings.groups, function(t, i) {
                    typeof i == "string" && (i = i.split(/\s/));
                    n.each(i, function(n, i) {
                        r[i] = t
                    })
                });
                t = this.settings.rules;
                n.each(t, function(i, r) {
                    t[i] = n.validator.normalizeRule(r)
                });
                n(this.currentForm).on("focusin.validate focusout.validate keyup.validate", ":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'], [type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], [type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], [type='radio'], [type='checkbox']", i).on("click.validate", "select, option, [type='radio'], [type='checkbox']", i);
                if (this.settings.invalidHandler)
                    n(this.currentForm).on("invalid-form.validate", this.settings.invalidHandler);
                n(this.currentForm).find("[required], [data-rule-required], .required").attr("aria-required", "true")
            },
            form: function() {
                return this.checkForm(),
                n.extend(this.submitted, this.errorMap),
                this.invalid = n.extend({}, this.errorMap),
                this.valid() || n(this.currentForm).triggerHandler("invalid-form", [this]),
                this.showErrors(),
                this.valid()
            },
            checkForm: function() {
                this.prepareForm();
                for (var n = 0, t = this.currentElements = this.elements(); t[n]; n++)
                    this.check(t[n]);
                return this.valid()
            },
            element: function(t) {
                var u = this.clean(t)
                  , i = this.validationTargetFor(u)
                  , r = !0;
                return this.lastElement = i,
                i === undefined ? delete this.invalid[u.name] : (this.prepareElement(i),
                this.currentElements = n(i),
                r = this.check(i) !== !1,
                r ? delete this.invalid[i.name] : this.invalid[i.name] = !0),
                n(t).attr("aria-invalid", !r),
                this.numberOfInvalids() || (this.toHide = this.toHide.add(this.containers)),
                this.showErrors(),
                r
            },
            showErrors: function(t) {
                if (t) {
                    n.extend(this.errorMap, t);
                    this.errorList = [];
                    for (var i in t)
                        this.errorList.push({
                            message: t[i],
                            element: this.findByName(i)[0]
                        });
                    this.successList = n.grep(this.successList, function(n) {
                        return !(n.name in t)
                    })
                }
                this.settings.showErrors ? this.settings.showErrors.call(this, this.errorMap, this.errorList) : this.defaultShowErrors()
            },
            resetForm: function() {
                n.fn.resetForm && n(this.currentForm).resetForm();
                this.submitted = {};
                this.lastElement = null;
                this.prepareForm();
                this.hideErrors();
                var t, i = this.elements().removeData("previousValue").removeAttr("aria-invalid");
                if (this.settings.unhighlight)
                    for (t = 0; i[t]; t++)
                        this.settings.unhighlight.call(this, i[t], this.settings.errorClass, "");
                else
                    i.removeClass(this.settings.errorClass)
            },
            numberOfInvalids: function() {
                return this.objectLength(this.invalid)
            },
            objectLength: function(n) {
                var t = 0;
                for (var i in n)
                    t++;
                return t
            },
            hideErrors: function() {
                this.hideThese(this.toHide)
            },
            hideThese: function(n) {
                n.not(this.containers).text("");
                this.addWrapper(n).hide()
            },
            valid: function() {
                return this.size() === 0
            },
            size: function() {
                return this.errorList.length
            },
            focusInvalid: function() {
                if (this.settings.focusInvalid)
                    try {
                        n(this.findLastActive() || this.errorList.length && this.errorList[0].element || []).filter(":visible").focus().trigger("focusin")
                    } catch (t) {}
            },
            findLastActive: function() {
                var t = this.lastActive;
                return t && n.grep(this.errorList, function(n) {
                    return n.element.name === t.name
                }).length === 1 && t
            },
            elements: function() {
                var t = this
                  , i = {};
                return n(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, :disabled").not(this.settings.ignore).filter(function() {
                    return (!this.name && t.settings.debug && window.console && console.error("%o has no name assigned", this),
                    this.name in i || !t.objectLength(n(this).rules())) ? !1 : (i[this.name] = !0,
                    !0)
                })
            },
            clean: function(t) {
                return n(t)[0]
            },
            errors: function() {
                var t = this.settings.errorClass.split(" ").join(".");
                return n(this.settings.errorElement + "." + t, this.errorContext)
            },
            reset: function() {
                this.successList = [];
                this.errorList = [];
                this.errorMap = {};
                this.toShow = n([]);
                this.toHide = n([]);
                this.currentElements = n([])
            },
            prepareForm: function() {
                this.reset();
                this.toHide = this.errors().add(this.containers)
            },
            prepareElement: function(n) {
                this.reset();
                this.toHide = this.errorsFor(n)
            },
            elementValue: function(t) {
                var i, u = n(t), r = t.type;
                return r === "radio" || r === "checkbox" ? this.findByName(t.name).filter(":checked").val() : r === "number" && typeof t.validity != "undefined" ? t.validity.badInput ? !1 : u.val() : (i = u.val(),
                typeof i == "string") ? i.replace(/\r/g, "") : i
            },
            check: function(t) {
                t = this.validationTargetFor(this.clean(t));
                var r = n(t).rules(), s = n.map(r, function(n, t) {
                    return t
                }).length, o = !1, h = this.elementValue(t), u, f, i;
                for (f in r) {
                    i = {
                        method: f,
                        parameters: r[f]
                    };
                    try {
                        if (u = n.validator.methods[f].call(this, h, t, i.parameters),
                        u === "dependency-mismatch" && s === 1) {
                            o = !0;
                            continue
                        }
                        if (o = !1,
                        u === "pending") {
                            this.toHide = this.toHide.not(this.errorsFor(t));
                            return
                        }
                        if (!u)
                            return this.formatAndAdd(t, i),
                            !1
                    } catch (e) {
                        this.settings.debug && window.console && console.log("Exception occurred when checking element " + t.id + ", check the '" + i.method + "' method.", e);
                        e instanceof TypeError && (e.message += ".  Exception occurred when checking element " + t.id + ", check the '" + i.method + "' method.");
                        throw e;
                    }
                }
                if (!o)
                    return this.objectLength(r) && this.successList.push(t),
                    !0
            },
            customDataMessage: function(t, i) {
                return n(t).data("msg" + i.charAt(0).toUpperCase() + i.substring(1).toLowerCase()) || n(t).data("msg")
            },
            customMessage: function(n, t) {
                var i = this.settings.messages[n];
                return i && (i.constructor === String ? i : i[t])
            },
            findDefined: function() {
                for (var n = 0; n < arguments.length; n++)
                    if (arguments[n] !== undefined)
                        return arguments[n];
                return undefined
            },
            defaultMessage: function(t, i) {
                return this.findDefined(this.customMessage(t.name, i), this.customDataMessage(t, i), !this.settings.ignoreTitle && t.title || undefined, n.validator.messages[i], "<strong>Warning: No message defined for " + t.name + "<\/strong>")
            },
            formatAndAdd: function(t, i) {
                var r = this.defaultMessage(t, i.method)
                  , u = /\$?\{(\d+)\}/g;
                typeof r == "function" ? r = r.call(this, i.parameters, t) : u.test(r) && (r = n.validator.format(r.replace(u, "{$1}"), i.parameters));
                this.errorList.push({
                    message: r,
                    element: t,
                    method: i.method
                });
                this.errorMap[t.name] = r;
                this.submitted[t.name] = r
            },
            addWrapper: function(n) {
                return this.settings.wrapper && (n = n.add(n.parent(this.settings.wrapper))),
                n
            },
            defaultShowErrors: function() {
                for (var i, t, n = 0; this.errorList[n]; n++)
                    t = this.errorList[n],
                    this.settings.highlight && this.settings.highlight.call(this, t.element, this.settings.errorClass, this.settings.validClass),
                    this.showLabel(t.element, t.message);
                if (this.errorList.length && (this.toShow = this.toShow.add(this.containers)),
                this.settings.success)
                    for (n = 0; this.successList[n]; n++)
                        this.showLabel(this.successList[n]);
                if (this.settings.unhighlight)
                    for (n = 0,
                    i = this.validElements(); i[n]; n++)
                        this.settings.unhighlight.call(this, i[n], this.settings.errorClass, this.settings.validClass);
                this.toHide = this.toHide.not(this.toShow);
                this.hideErrors();
                this.addWrapper(this.toShow).show()
            },
            validElements: function() {
                return this.currentElements.not(this.invalidElements())
            },
            invalidElements: function() {
                return n(this.errorList).map(function() {
                    return this.element
                })
            },
            showLabel: function(t, i) {
                var u, o, e, r = this.errorsFor(t), s = this.idOrName(t), f = n(t).attr("aria-describedby");
                r.length ? (r.removeClass(this.settings.validClass).addClass(this.settings.errorClass),
                r.html(i)) : (r = n("<" + this.settings.errorElement + ">").attr("id", s + "-error").addClass(this.settings.errorClass).html(i || ""),
                u = r,
                this.settings.wrapper && (u = r.hide().show().wrap("<" + this.settings.wrapper + "/>").parent()),
                this.labelContainer.length ? this.labelContainer.append(u) : this.settings.errorPlacement ? this.settings.errorPlacement(u, n(t)) : u.insertAfter(t),
                r.is("label") ? r.attr("for", s) : r.parents("label[for='" + s + "']").length === 0 && (e = r.attr("id").replace(/(:|\.|\[|\]|\$)/g, "\\$1"),
                f ? f.match(new RegExp("\\b" + e + "\\b")) || (f += " " + e) : f = e,
                n(t).attr("aria-describedby", f),
                o = this.groups[t.name],
                o && n.each(this.groups, function(t, i) {
                    i === o && n("[name='" + t + "']", this.currentForm).attr("aria-describedby", r.attr("id"))
                })));
                !i && this.settings.success && (r.text(""),
                typeof this.settings.success == "string" ? r.addClass(this.settings.success) : this.settings.success(r, t));
                this.toShow = this.toShow.add(r)
            },
            errorsFor: function(t) {
                var r = this.idOrName(t)
                  , u = n(t).attr("aria-describedby")
                  , i = "label[for='" + r + "'], label[for='" + r + "'] *";
                return u && (i = i + ", #" + u.replace(/\s+/g, ", #")),
                this.errors().filter(i)
            },
            idOrName: function(n) {
                return this.groups[n.name] || (this.checkable(n) ? n.name : n.id || n.name)
            },
            validationTargetFor: function(t) {
                return this.checkable(t) && (t = this.findByName(t.name)),
                n(t).not(this.settings.ignore)[0]
            },
            checkable: function(n) {
                return /radio|checkbox/i.test(n.type)
            },
            findByName: function(t) {
                return n(this.currentForm).find("[name='" + t + "']")
            },
            getLength: function(t, i) {
                switch (i.nodeName.toLowerCase()) {
                case "select":
                    return n("option:selected", i).length;
                case "input":
                    if (this.checkable(i))
                        return this.findByName(i.name).filter(":checked").length
                }
                return t.length
            },
            depend: function(n, t) {
                return this.dependTypes[typeof n] ? this.dependTypes[typeof n](n, t) : !0
            },
            dependTypes: {
                boolean: function(n) {
                    return n
                },
                string: function(t, i) {
                    return !!n(t, i.form).length
                },
                "function": function(n, t) {
                    return n(t)
                }
            },
            optional: function(t) {
                var i = this.elementValue(t);
                return !n.validator.methods.required.call(this, i, t) && "dependency-mismatch"
            },
            startRequest: function(n) {
                this.pending[n.name] || (this.pendingRequest++,
                this.pending[n.name] = !0)
            },
            stopRequest: function(t, i) {
                this.pendingRequest--;
                this.pendingRequest < 0 && (this.pendingRequest = 0);
                delete this.pending[t.name];
                i && this.pendingRequest === 0 && this.formSubmitted && this.form() ? (n(this.currentForm).submit(),
                this.formSubmitted = !1) : !i && this.pendingRequest === 0 && this.formSubmitted && (n(this.currentForm).triggerHandler("invalid-form", [this]),
                this.formSubmitted = !1)
            },
            previousValue: function(t) {
                return n.data(t, "previousValue") || n.data(t, "previousValue", {
                    old: null,
                    valid: !0,
                    message: this.defaultMessage(t, "remote")
                })
            },
            destroy: function() {
                this.resetForm();
                n(this.currentForm).off(".validate").removeData("validator")
            }
        },
        classRuleSettings: {
            required: {
                required: !0
            },
            email: {
                email: !0
            },
            url: {
                url: !0
            },
            date: {
                date: !0
            },
            dateISO: {
                dateISO: !0
            },
            number: {
                number: !0
            },
            digits: {
                digits: !0
            },
            creditcard: {
                creditcard: !0
            }
        },
        addClassRules: function(t, i) {
            t.constructor === String ? this.classRuleSettings[t] = i : n.extend(this.classRuleSettings, t)
        },
        classRules: function(t) {
            var i = {}
              , r = n(t).attr("class");
            return r && n.each(r.split(" "), function() {
                this in n.validator.classRuleSettings && n.extend(i, n.validator.classRuleSettings[this])
            }),
            i
        },
        normalizeAttributeRule: function(n, t, i, r) {
            /min|max/.test(i) && (t === null || /number|range|text/.test(t)) && (r = Number(r),
            isNaN(r) && (r = undefined));
            r || r === 0 ? n[i] = r : t === i && t !== "range" && (n[i] = !0)
        },
        attributeRules: function(t) {
            var r = {}, f = n(t), e = t.getAttribute("type"), u, i;
            for (u in n.validator.methods)
                u === "required" ? (i = t.getAttribute(u),
                i === "" && (i = !0),
                i = !!i) : i = f.attr(u),
                this.normalizeAttributeRule(r, e, u, i);
            return r.maxlength && /-1|2147483647|524288/.test(r.maxlength) && delete r.maxlength,
            r
        },
        dataRules: function(t) {
            var r = {}, f = n(t), e = t.getAttribute("type"), i, u;
            for (i in n.validator.methods)
                u = f.data("rule" + i.charAt(0).toUpperCase() + i.substring(1).toLowerCase()),
                this.normalizeAttributeRule(r, e, i, u);
            return r
        },
        staticRules: function(t) {
            var i = {}
              , r = n.data(t.form, "validator");
            return r.settings.rules && (i = n.validator.normalizeRule(r.settings.rules[t.name]) || {}),
            i
        },
        normalizeRules: function(t, i) {
            return n.each(t, function(r, u) {
                if (u === !1) {
                    delete t[r];
                    return
                }
                if (u.param || u.depends) {
                    var f = !0;
                    switch (typeof u.depends) {
                    case "string":
                        f = !!n(u.depends, i.form).length;
                        break;
                    case "function":
                        f = u.depends.call(i, i)
                    }
                    f ? t[r] = u.param !== undefined ? u.param : !0 : delete t[r]
                }
            }),
            n.each(t, function(r, u) {
                t[r] = n.isFunction(u) ? u(i) : u
            }),
            n.each(["minlength", "maxlength"], function() {
                t[this] && (t[this] = Number(t[this]))
            }),
            n.each(["rangelength", "range"], function() {
                var i;
                t[this] && (n.isArray(t[this]) ? t[this] = [Number(t[this][0]), Number(t[this][1])] : typeof t[this] == "string" && (i = t[this].replace(/[\[\]]/g, "").split(/[\s,]+/),
                t[this] = [Number(i[0]), Number(i[1])]))
            }),
            n.validator.autoCreateRanges && (t.min != null && t.max != null && (t.range = [t.min, t.max],
            delete t.min,
            delete t.max),
            t.minlength != null && t.maxlength != null && (t.rangelength = [t.minlength, t.maxlength],
            delete t.minlength,
            delete t.maxlength)),
            t
        },
        normalizeRule: function(t) {
            if (typeof t == "string") {
                var i = {};
                n.each(t.split(/\s/), function() {
                    i[this] = !0
                });
                t = i
            }
            return t
        },
        addMethod: function(t, i, r) {
            n.validator.methods[t] = i;
            n.validator.messages[t] = r !== undefined ? r : n.validator.messages[t];
            i.length < 3 && n.validator.addClassRules(t, n.validator.normalizeRule(t))
        },
        methods: {
            required: function(t, i, r) {
                if (!this.depend(r, i))
                    return "dependency-mismatch";
                if (i.nodeName.toLowerCase() === "select") {
                    var u = n(i).val();
                    return u && u.length > 0
                }
                return this.checkable(i) ? this.getLength(t, i) > 0 : t.length > 0
            },
            email: function(n, t) {
                return this.optional(t) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(n)
            },
            url: function(n, t) {
                return this.optional(t) || /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})).?)(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(n)
            },
            date: function(n, t) {
                return this.optional(t) || !/Invalid|NaN/.test(new Date(n).toString())
            },
            dateISO: function(n, t) {
                return this.optional(t) || /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(n)
            },
            number: function(n, t) {
                return this.optional(t) || /^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(n)
            },
            digits: function(n, t) {
                return this.optional(t) || /^\d+$/.test(n)
            },
            creditcard: function(n, t) {
                if (this.optional(t))
                    return "dependency-mismatch";
                if (/[^0-9 \-]+/.test(n))
                    return !1;
                var f = 0, i = 0, u = !1, r, e;
                if (n = n.replace(/\D/g, ""),
                n.length < 13 || n.length > 19)
                    return !1;
                for (r = n.length - 1; r >= 0; r--)
                    e = n.charAt(r),
                    i = parseInt(e, 10),
                    u && (i *= 2) > 9 && (i -= 9),
                    f += i,
                    u = !u;
                return f % 10 == 0
            },
            minlength: function(t, i, r) {
                var u = n.isArray(t) ? t.length : this.getLength(t, i);
                return this.optional(i) || u >= r
            },
            maxlength: function(t, i, r) {
                var u = n.isArray(t) ? t.length : this.getLength(t, i);
                return this.optional(i) || u <= r
            },
            rangelength: function(t, i, r) {
                var u = n.isArray(t) ? t.length : this.getLength(t, i);
                return this.optional(i) || u >= r[0] && u <= r[1]
            },
            min: function(n, t, i) {
                return this.optional(t) || n >= i
            },
            max: function(n, t, i) {
                return this.optional(t) || n <= i
            },
            range: function(n, t, i) {
                return this.optional(t) || n >= i[0] && n <= i[1]
            },
            equalTo: function(t, i, r) {
                var u = n(r);
                if (this.settings.onfocusout)
                    u.off(".validate-equalTo").on("blur.validate-equalTo", function() {
                        n(i).valid()
                    });
                return t === u.val()
            },
            remote: function(t, i, r) {
                if (this.optional(i))
                    return "dependency-mismatch";
                var f = this.previousValue(i), u, e;
                return (this.settings.messages[i.name] || (this.settings.messages[i.name] = {}),
                f.originalMessage = this.settings.messages[i.name].remote,
                this.settings.messages[i.name].remote = f.message,
                r = typeof r == "string" && {
                    url: r
                } || r,
                f.old === t) ? f.valid : (f.old = t,
                u = this,
                this.startRequest(i),
                e = {},
                e[i.name] = t,
                n.ajax(n.extend(!0, {
                    mode: "abort",
                    port: "validate" + i.name,
                    dataType: "json",
                    data: e,
                    context: u.currentForm,
                    success: function(r) {
                        var o = r === !0 || r === "true", s, e, h;
                        u.settings.messages[i.name].remote = f.originalMessage;
                        o ? (h = u.formSubmitted,
                        u.prepareElement(i),
                        u.formSubmitted = h,
                        u.successList.push(i),
                        delete u.invalid[i.name],
                        u.showErrors()) : (s = {},
                        e = r || u.defaultMessage(i, "remote"),
                        s[i.name] = f.message = n.isFunction(e) ? e(t) : e,
                        u.invalid[i.name] = !0,
                        u.showErrors(s));
                        f.valid = o;
                        u.stopRequest(i, o)
                    }
                }, r)),
                "pending")
            }
        }
    });
    var t = {}, i;
    n.ajaxPrefilter ? n.ajaxPrefilter(function(n, i, r) {
        var u = n.port;
        n.mode === "abort" && (t[u] && t[u].abort(),
        t[u] = r)
    }) : (i = n.ajax,
    n.ajax = function(r) {
        var f = ("mode"in r ? r : n.ajaxSettings).mode
          , u = ("port"in r ? r : n.ajaxSettings).port;
        return f === "abort" ? (t[u] && t[u].abort(),
        t[u] = i.apply(this, arguments),
        t[u]) : i.apply(this, arguments)
    }
    )
});
var domainTracking = ""
  , eventCategory = "CartV4"
  , eventAction = {
    click: "click",
    detail: "detail",
    add: "add",
    remove: "remove",
    checkout: "checkout",
    checkout_option: "checkout_option",
    purchase: "purchase",
    refund: "refund",
    promo_click: "promo_click"
}
  , eventLabel = {
    viewCheckout: "viewCheckout",
    updateColor: "update color",
    updateQuantity: "update quantity",
    useCoupon: "use Coupon",
    useVat: "use VAT",
    shipatHome: "ship at home",
    shipatHomeclickProvince: "ship at home click province ",
    shipatHomeclickDistict: "ship at home click distict",
    shipatHomeclickDay: "ship at home click day",
    shipatHomeclickHour: "ship at home click hour",
    shipatHomeclickCard: "ship at home click card",
    shipatStore: "ship at store",
    shipatStoreclickProvince: "ship at store click province ",
    shipatStoreclickDistict: "ship at store click distict",
    shipatStoreclickStore: "ship at store click store",
    shipatStoreclickStoreMore: "ship at store click store more",
    shipatStoreclickDay: "ship at store click day",
    buymore: "buy more",
    buyoffline: " buy offline",
    buyonline: " buy online",
    buyonlineatm: " buy online atm",
    buyonlinevisa: " buy online visa",
    buyonlineback: " buy online back",
    updateUser: "update user",
    technicalSupportShiping: "technical support shiping"
}
  , productga = {};
cartConfig = {
    colorclicksubmit: !0,
    allclicksubmit: !1,
    linksubmitorder: "/aj/OrderV4/CartOrderSubmit",
    link_store_loaddistict: "/aj/OrderV4/GetDistrictStoreByProvince",
    link_store_loadprovince: "/aj/OrderV4/GetProvinceStore",
    link_loadstore: "/aj/OrderV4/LoadAllStoreByProductCode"
};
$(document).ready(function() {
    $(".malefemale label").click(function() {
        $(this).hasClass("choose") || ($(".malefemale label.choose").removeClass("choose"),
        $(this).addClass("choose"),
        $(this).hasClass("anh") ? $("#BillingAddress_iGender").val("1") : $("#BillingAddress_iGender").val("0"),
        $("#BillingAddress_iGender-error").remove(),
        updateInfoTmp())
    });
    $("input.saveinfo").on("input", function() {
        updateInfoTmp()
    });
    $(".address label").click(function() {
        (ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatHome),
        ganew_stepCart(2, "shipatHome"),
        $(".boxCompany").show(),
        $(this).hasClass("choose")) || ($(".area_other div label.choose").removeClass("choose"),
        $(this).addClass("choose"),
        ShowTabHome(),
        $(".cathe").hasClass("check") && $(".payonline").hide(),
        $("body,html").animate({
            scrollTop: $(".address").position().top
        }, 600),
        $(".showtimeship_address .listdate span:first").click())
    });
    $(".supermarket label").click(function() {
        (ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatStore),
        ganew_stepCart(2, "shipatStore"),
        $(".boxCompany").show(),
        $(this).hasClass("choose")) || ($(".area_other div label.choose").removeClass("choose"),
        $(this).addClass("choose"),
        ShowTabStore(),
        flag_firstloadstore && LoadStore(),
        flag_firstloadstore = !1,
        $(".payonline").show(),
        $("body,html").animate({
            scrollTop: $(".supermarket").position().top
        }, 600))
    });
    $(".city").click(function() {
        $(".layer").not($(this).next()).hide();
        $(this).next().slideToggle()
    });
    $(".dist").click(function() {
        $(".layer").not($(this).next()).hide();
        $(this).next().slideToggle()
    });
    $(".firstlayer").click(function() {
        $(".layer").not($(this).next()).hide();
        $(this).next().slideToggle()
    });
    $(".htkt").click(function() {
        $(this).toggleClass("check");
        $(this).hasClass("check") ? (ganew_sendEvent(eventCategory, eventAction.click, eventLabel.technicalSupportShiping),
        $("#IsTechSupportShiper").val(1)) : $("#IsTechSupportShiper").val(0)
    });
    $(".cathe").click(function() {
        $(this).toggleClass("check");
        $(this).hasClass("check") ? (ganew_sendEvent(eventCategory, eventAction.click, eventLabel.shipatHomeclickCard),
        $("#PaymentMethod").val(1)) : $("#PaymentMethod").val(0);
        $(".payonline").slideToggle()
    });
    $(".vat").click(function() {
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.useVat);
        $(this).toggleClass("check");
        $(".infocompany").slideToggle(function() {
            $(".infocompany input").prop("disabled", !$(".infocompany").is(":visible"))
        })
    });
    $(".payonline div").click(function() {
        if (ganew_sendEvent(eventCategory, eventAction.click, eventLabel.buyonline),
        $(".message").html(""),
        parseInt($("#TotalMoneyAll").val()) > 5e7)
            return $(".message").html("Rất tiếc, hiện tại TGDĐ chỉ hỗ trợ thanh toán trực tuyến với những đơn hàng có giá trị nhỏ hơn 50.000.000đ"),
            !1;
        $(".choosepayment").hide();
        $(".onlinemethod").show();
        $(".atm").show();
        $(".visa").show()
    });
    $(".onlinemethod .rechoose").click(function() {
        $(".choosepayment").show();
        $(".onlinemethod").hide();
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.buyonlineback)
    });
    $(".onlinemethod .atm").click(function() {
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.buyonlineatm);
        ganew_stepCart(4, "atm");
        $("#PaymentMethod").val(3);
        CheckOrderValidateNew()
    });
    $(".onlinemethod .visa").click(function() {
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.buyonlinevisa);
        ganew_stepCart(4, "buyonlinevisa");
        $("#PaymentMethod").val(2);
        CheckOrderValidateNew()
    });
    $(".payoffline").click(function() {
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.buyoffline);
        ganew_stepCart(4, "buyoffline");
        $("#PaymentMethod").val(0);
        CheckOrderValidateNew()
    });
    AddEventOrderAccessory();
    AddEventAtHome();
    AddEventAtStore();
    $(".searchlocal input[type=text]").live("keyup", function() {
        var n = this.value;
        $(this).parents(".searchlocal").next().find("a").each(function() {
            var t = $(this).text()
              , i = /[\-\[\]{}()*+?.,\\\^$|#\s]/g
              , r = new RegExp(n.replace(i, "\\$&"),"gi")
              , u = new RegExp(locdau(n).replace(i, "\\$&"),"gi");
            t.search(r) !== -1 || locdau(t).search(r) !== -1 || locdau(t).search(u) !== -1 ? $(this).show() : $(this).hide()
        })
    });
    $(".searchstore input[type=text]").live("keyup", function() {
        var n = this.value;
        n.length > 0 ? $(".listmarket").addClass("searchs") : $(".listmarket").removeClass("searchs");
        $(".listmarket").find("li").each(function() {
            var t = $(this).text()
              , i = /[\-\[\]{}()*+?.,\\\^$|#\s]/g
              , r = new RegExp(n.replace(i, "\\$&"),"gi")
              , u = new RegExp(locdau(n).replace(i, "\\$&"),"gi");
            t.search(r) !== -1 || locdau(t).search(r) !== -1 || locdau(t).search(u) !== -1 ? $(this).addClass("s") : $(this).removeClass("s")
        })
    });
    $(".boxCouponCode button").live("click", function() {
        $("#CouponCode-error").hide();
        POSTAjax("/aj/OrderV4/CheckCouponCodeCart", {
            CouponCode: $("#CouponCode").val()
        }, BeforeSendAjax, function(n) {
            if ($(n).find(".detail_cart .listorder").length == 0)
                return window.location.href = "/gio-hang",
                !1;
            var t = $(n).find(".message").html();
            t ? ($("#CouponCode-error").html(t),
            $("#CouponCode-error").show()) : ($(".detail_cart").html($(n).find(".detail_cart").html()),
            AddEventOrderAccessory());
            EndSendAjax()
        }, function() {}, !0)
    });
    $(".buymore").click(function() {
        return ganew_sendEvent(eventCategory, eventAction.click, eventLabel.buymore),
        !0
    });
    $(".infoold .change").click(function() {
        $(".infoold").hide();
        $(".infouser").show();
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.updateUser)
    });
    $(".boxCouponCode .textcode").live("click", function() {
        ganew_sendEvent(eventCategory, eventAction.click, eventLabel.useCoupon);
        $(".boxCouponCode .inputcode").show()
    });
    LoadAllProvinceAtHomeByProductCode();
    addEventPromotionAcc();
    $(".showtimeship_address .listdate span:first").click()
});
f_CartSubmitOrder = !1;
url_LoadAllDistrictByProvince = "/aj/OrderV4/GetDistrictByProvinceandProductCode";
url_LoadAllWardByDistrict = "/aj/OrderV4/GetWardByDistrict";
flag_firstloadstore = !0;
updateInfoTmp = function() {
    $("#IsShipAtStore").val() === "true" && $(".area_market .EmailSecond").length > 0 && ($("#BillingAddress_Email").val($(".area_market .EmailSecond").val()),
    $(".area_address .EmailSecond").val($(".area_market .EmailSecond").val()));
    $("#IsShipAtHome").val() === "true" && $(".area_address .EmailSecond").length > 0 && ($("#BillingAddress_Email").val($(".area_address .EmailSecond").val()),
    $(".area_market .EmailSecond").val($(".area_address .EmailSecond").val()));
    // $.ajax({
    //     async: !0,
    //     data: $("#wrap_cart form").serialize(),
    //     type: "POST",
    //     error: function() {
    //         console.log("Close notification error")
    //     },
    //     success: function(n) {
    //         console.log(n)
    //     },
    //     url: "/aj/OrderV4/UpdateInfoCart"
    // })
}
;
String.prototype.trimRight = function(n) {
    return n === undefined && (n = "s"),
    this.replace(new RegExp("[" + n + "]+$"), "")
}
;
jQuery.validator.addMethod("ShipAtHome", function(n, t) {
    return $("#IsShipAtHome").val() != "true" ? !0 : !this.optional(t)
}, "");
jQuery.validator.addMethod("ShipAtStore", function(n, t) {
    return $("#IsShipAtStore").val() != "true" ? !0 : !this.optional(t)
}, "Bạn phải chọn siêu thị");
jQuery.validator.addMethod("IsCompany", function(n, t) {
    var i = $('#formtest :input[name="CompanyInfo.CompanyName"]');
    return i.length == 0 || i.val() == "" ? !0 : !this.optional(t)
}, "Bạn phải nhập thông tin");
jQuery.validator.addClassRules("min1", {
    min: 1
});
jQuery.validator.addClassRules("min9", {
    minlength: 9
});
jQuery.validator.addMethod("groupchoose0", function() {
    $(".message").html("");
    var n = $(".groupchoose0").length;
    return n == 0 ? !0 : $(".groupchoose0").val() != "-1" ? !0 : ($.validator.messages.groupchoose0 = "Vui lòng chọn khuyến mãi",
    $(".message").html("Vui lòng chọn khuyến mãi"),
    !1)
}, "s");
jQuery.validator.addMethod("groupchoose1", function() {
    $(".message").html("");
    var n = $(".groupchoose1").length;
    return n == 0 ? !0 : $(".groupchoose1").val() != "-1" ? !0 : ($.validator.messages.groupchoose1 = "Vui lòng chọn khuyến mãi",
    $(".message").html("Vui lòng chọn khuyến mãi"),
    !1)
}, "s");
jQuery.validator.addMethod("groupchoose2", function() {
    $(".message").html("");
    var n = $(".groupchoose2").length;
    return n == 0 ? !0 : $(".groupchoose2").val() != "-1" ? !0 : ($.validator.messages.groupchoose2 = "Vui lòng chọn khuyến mãi",
    $(".message").html("Vui lòng chọn khuyến mãi"),
    !1)
}, "s");
jQuery.validator.addMethod("validatePhoneNumber", function() {
    var t = $('#formtest :input[name="BillingAddress.PhoneNumber"]'), r = {
        PhoneNumber: t.val()
    }, i, n;
    return t.is("input[type='hidden']") ? !0 : (i = $.ajax({
        type: "POST",
        url: "/aj/OrderV2/CheckValidatePhone",
        dataType: "json",
        async: !1,
        data: r,
        success: function() {
            return 0
        },
        error: function() {
            return alert("ajax loading error... ... " + url + query),
            0
        }
    }).responseText,
    n = JSON.parse(i),
    $.validator.messages.validatePhoneNumber = n.errors,
    n.success)
}, "s");
jQuery.validator.addMethod("validateCompanyTaxno", function() {
    var n = $('#formtest :input[name="CompanyInfo.CompanyTaxno"]'), i, t;
    return n.length == 0 || n.val() == "" ? !0 : (data = {
        CompanyTaxno: n.val()
    },
    i = $.ajax({
        type: "POST",
        url: "/aj/OrderV2/CheckValidateCompanyTaxno",
        dataType: "json",
        async: !1,
        data: data,
        success: function() {
            return 0
        },
        error: function() {
            return alert("ajax loading error... ... " + url + query),
            0
        }
    }).responseText,
    t = JSON.parse(i),
    $.validator.messages.validateCompanyTaxno = t.errors,
    t.success)
}, "");
jQuery.validator.addMethod("validateiGender", function() {
    var t = $('#formtest :input[name="BillingAddress.iGender"]'), i, n;
    return t.val() == "1" || t.val() == "0" ? !0 : (i = '{"success":false,"errors":"Mời quý khách chọn danh xưng"}',
    n = JSON.parse(i),
    $.validator.messages.validateiGender = n.errors,
    n.success)
}, "");
jQuery.validator.addMethod("validateCaptcha", function() {
    var i = $('#formtest :input[name="Captcha"]'), t, n;
    return !$(".captcha").is(":visible") || i.val().length > 0 ? !0 : (t = '{"success":false,"errors":"Nhập mã bảo mật"}',
    n = JSON.parse(t),
    $.validator.messages.validateCaptcha = n.errors,
    n.success)
}, "");
form = $("#formtest");
form.validate({
    ignore: "",
    onkeyup: !1,
    debug: !0,
    rules: {
        "BillingAddress.iGender": {
            validateiGender: !0
        },
        "BillingAddress.FullName": "required",
        "BillingAddress.PhoneNumber": {
            required: !0,
            minlength: 10,
            validatePhoneNumber: !0
        },
        "CompanyInfo.CompanyTaxno": {
            validateCompanyTaxno: !0
        },
        "BillingAddress.Email": "email",
        EmailSecondHome: "email",
        EmailSecondStore: "email",
        ProductCode: "required",
        Captcha: {
            validateCaptcha: !0
        }
    },
    messages: {
        "BillingAddress.FullName": "Quý khách cần điền tên",
        "BillingAddress.PhoneNumber": {
            required: "Quý khách cần điền số điện thoại",
            minlength: "Số điện thoại ít nhất là 10 số"
        },
        "BillingAddress.Email": {
            email: "Email chưa đúng định dạng"
        },
        EmailSecondHome: {
            email: "Email chưa đúng định dạng"
        },
        EmailSecondStore: {
            email: "Email chưa đúng định dạng"
        },
        ProductCode: "Quý khách vui lòng chọn màu",
        "BillingAddress.Address": "Quý khách vui lòng điền số nhà, tên đường, phường / xã",
        "CompanyInfo.CompanyAddress": "Quý khách cần điền địa chỉ công ty",
        "CompanyInfo.CompanyTaxno": {
            required: "Quý khách cần điền mã số thuế"
        },
        "BillingAddress.StoreId": {
            min: "Quý khách vui lòng chọn siêu thị để nhận hàng"
        },
        "BillingAddress.DistictId": {
            min: "Quý khách vui lòng chọn quận huyện"
        },
        "BillingAddress.WardId": {
            min: "Quý khách vui lòng chọn phường xã"
        },
        "BillingAddress.CMND": {
            minlength: "Số CMND chỉ từ 9 đến 12 số"
        }
    }
});
/*
//# sourceMappingURL=vnn_cart.min.js.map
*/


$(document).on('change','.cart_e',function(){        
    u_cart();
});
