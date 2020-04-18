function calcCharges(amount) {
    var flatFee = 100;
    var charges = 1.5 / 100;

    amount = amount < 2500 ? amount : amount + flatFee;

    var price = amount / (1 - charges);

    price = price - amount > 2000 ? amount + 2000 : price;

    return Math.ceil(price);
    //return

}
