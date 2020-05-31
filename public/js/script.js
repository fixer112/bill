function calcCharges(amount, charges = 1.5, flatFee = 50) {
    amount = +amount;

    var charges = charges / 100;

    amount = amount < 2500 ? amount : amount + flatFee;

    //return amount;
    var price = amount / (1 - charges);

    //return price;

    price = price - amount > 2000 ? amount + 2000 : price;

    return Math.ceil(price);
    //return

}

function getLastString(string, delimiter = '-') {
    var strings = string.split(delimiter);

    return strings[strings.length - 1];
}

function guestPaystack(amount, data, key) {
    amount = amount >= 2500 ? +amount + 50 : amount;
    var handler = PaystackPop.setup({
        key: key,
        amount: calcCharges(amount) * 100,
        currency: "NGN",
        email: "guestpayment@altechtic.com",
        metadata: data,

        callback: function(response) {
            console.log("/verify/" + data['reason'] + '/' + response.reference);
            window.location.replace("/verify/" + data['reason'] + '/' + response.reference);
            //alert('success. transaction ref is ' + response.reference);
        },
        onClose: function() {
            alert('Payment Cancelled');
        }
    });
    handler.openIframe();
};

function processPayment(data, balance, billEnabled, errorMessage, key) {

    if (balance < data['amount'] || !billEnabled) {
        $.notify({
            // options
            message: errorMessage
        }, {
            // settings
            type: 'danger'
        });
        //$.bootstrapGrowl("This is another test.", { type: 'success' });
        return;
    }

    guestPaystack(data['amount'], data, key);

}

function wpChat() {
    $('.floating-wpp').floatingWhatsApp({

        phone: '2349049392607',

        size: '50px',

        position: "right",

        popupMessage: 'Hello!!!, how can we help you',

        showPopup: true,

        //message: 'Message To Send',

        headerTitle: 'MoniWallet Whatsapp'

    });
}