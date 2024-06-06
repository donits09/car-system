function convertToWords(number) {
    var ones = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
    var teens = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
    var tens = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

    function convertGroup(num) {
        var result = "";
        if (num >= 100) {
            result += ones[Math.floor(num / 100)] + " Hundred ";
            num %= 100;
        }
        if (num >= 10 && num <= 19) {
            result += teens[num - 10];
        } else if (num >= 20) {
            result += tens[Math.floor(num / 10)];
            if (num % 10 > 0) {
                result += " " + ones[num % 10];
            }
        } else if (num > 0) {
            result += ones[num];
        }
        return result;
    }

    var result = "";
    if (number >= 1000000) {
        result += convertGroup(Math.floor(number / 1000000)) + " Million ";
        number %= 1000000;
    }
    if (number >= 1000) {
        result += convertGroup(Math.floor(number / 1000)) + " Thousand ";
        number %= 1000;
    }
    if (number >= 1) {
        result += convertGroup(Math.floor(number));
    }

    var decimalPart = number % 1;
    if (decimalPart > 0) {
        result += " and " + (decimalPart * 100).toFixed(0) + "/100";
    }

    result = result.trim() + " Pesos Only";

    return result;
}

function calculateVisibleCharacters(element) {
    var style = window.getComputedStyle(element);
    var width = element.clientWidth;
    var paddingRight = parseFloat(style.paddingRight);
    var paddingLeft = parseFloat(style.paddingLeft);
    var marginRight = parseFloat(style.marginRight);
    var marginLeft = parseFloat(style.marginLeft);
    var borderRight = parseFloat(style.borderRightWidth);
    var borderLeft = parseFloat(style.borderLeftWidth);

    var visibleWidth = width - paddingRight - paddingLeft - marginRight - marginLeft - borderRight - borderLeft;
    var fontSize = parseFloat(style.fontSize);
    var characters = Math.floor(visibleWidth / (fontSize * 0.6)); 

    return characters;
}

function convertCarAmountToWords() {
    var carAmountElement = document.getElementById("c_car_amount");
    var carAmountWordsElement = document.getElementById("c_car_amount_words");

    var carAmount = parseFloat(carAmountElement.value.replace(/,/g, ''));
    var carAmountWords = convertToWords(carAmount);

    carAmountWordsElement.value = carAmountWords;


    var lineHeight = parseInt(window.getComputedStyle(carAmountWordsElement).lineHeight);
    var lines = carAmountWordsElement.scrollHeight / lineHeight;
    var fontSize = 15; 
    if (lines > 1) {
        fontSize -= (lines - 1) * 2; 
    }
    carAmountWordsElement.style.fontSize = fontSize + "px";
}
