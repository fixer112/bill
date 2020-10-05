function calcCharges(amount, charges = 1.5, flatFee = 0) {
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

function guestRave(amount, data, key, reason, ref) {

    var x = getpaidSetup({
        PBFPubKey: key,
        customer_email: 'guestpayment@altechtic.com',
        amount: amount,
        //customer_phone: '{{request()->user->number}}',
        currency: "NGN",
        txref: "mwg-" + ref,
        meta: data,
        onclose: function() {
            //alert('Payment Cancelled');
        },
        callback: function(response) {
            var txref = response.data.data.txRef; // collect txRef returned and pass to a server page to complete status check.
            console.log(response.data.data);
            window.location.replace("/verify/" + reason + '/' + txref);
            /*if (
                response.data.chargeResponseCode == "00" ||
                response.data.chargeResponseCode == "0"
                ) {
                // redirect to a success page
                window.location.replace("/verify/wallet/fund/"+txref);
                } else {
                // redirect to a failure page.
            
                }*/

            x.close(); // use this to close the modal immediately after payment.
        }
    });

}

function processPayment(amount, data, balance, billEnabled, errorMessage, key, reason, ref) {

    if (balance < amount || !billEnabled) {
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

    guestRave(amount, data, key, reason, ref);

}

function wpChat(position = 'right') {
    $('.floating-wpp').floatingWhatsApp({

        phone: '2349013428002',

        size: '50px',

        position: position,

        popupMessage: 'Hello!!!, how can we help you',

        showPopup: true,

        //message: 'Message To Send',

        headerTitle: 'MoniWallet Whatsapp'

    });
}