function calcCharges(amount) {
    amount = +amount;
    var flatFee = 100;
    var charges = 1.5 / 100;

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
