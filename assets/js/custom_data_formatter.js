function commaFormatterWithSatuan(value, row) {
    return parseFloat(value).toLocaleString('en') + " " + row["satuan"];
}

function commaFormatter(value, row) {
    return parseFloat(value).toLocaleString('en');
}