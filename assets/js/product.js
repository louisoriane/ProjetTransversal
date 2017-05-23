$(function() {
    $("#search-input").keydown(function(e) {
        if (e.which == 13) {
            var product = $('#search-input').val();
            var dataString = 'product=' + product;
            if (product == '') {
                alert("empty");
            }
            else {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "?action=search",
                    data: dataString,
                    cache: false,
                    success: function (result) {
                        if(result.charAt(0) == "<"){
                            alert('invalid data');
                        }
                        else{
                            // console.log(typeof result);
                            displayResult(result);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("error");
                    }
                });
            }
        }
    });

    $("#search-input2").keydown(function(e) {
        if (e.which == 13) {
            var product = $('#search-input2').val();
            var dataString = 'product=' + product;
            if (product == '') {
                alert("empty");
            }
            else {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "?action=search",
                    data: dataString,
                    cache: false,
                    success: function (result) {
                        if(result.charAt(0) == "<"){
                            alert('invalid data');
                        }
                        else{
                            displayResult2(result, product);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        alert("error");
                    }
                });
            }
        }
    });

    $('#search').click(function(e) {
        var product = $('#search-input').val();
        var dataString = 'product=' + product;
        if (product == '') {
            alert("empty");
        }
        else {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "?action=search",
                data: dataString,
                cache: false,
                success: function (result) {
                    if(result.charAt(0) == "<"){
                        alert('invalid data');
                    }
                    else{
                        displayResult(result);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert("error");
                }
            });
        }
    });

    function displayResult(result) {
        $('#result-product').empty();
        var arr = JSON.parse(result);
        var arraySorted = bubbleSort(arr);
        var empty = false;
        arraySorted.forEach(function(element) {
            var price = element.price;
            var secondPart = price.substring(price.length - 2,price.length);
            var firstPart = price.substring(0,price.length - 2);
            price = firstPart + ',' + secondPart;

            $('#result-product').append('<div class="offre-info"><img src="' + element.img +'" alt="' + element.title + '" id="offre-icon"><form action="?action=product" method="POST"><input type="hidden" value="'
                +element.title+'" name="name"><input type="hidden" value="'+element.brand+'" name="seller"><input type="hidden" value="'+element.img+'" name="img"><input type="submit" value="'+price+' &euro;" name="price"></form></div>');
            empty = true;
        });
        if(!empty){
            $('#result-product').append('<li><span id="product_place">Cet article n\'éxiste pas</span>');
        }
    }

    function displayResult2(result, name) {
        $('#result-product2').empty();
        var arr = JSON.parse(result);
        var arraySorted = bubbleSort(arr);
        var empty = false;
        arraySorted.forEach(function(element) {
            var price = element.price;
            var secondPart = price.substring(price.length - 2,price.length);
            var firstPart = price.substring(0,price.length - 2);
            price = firstPart + ',' + secondPart;
            
            $('#result-product2').append('<div class="product-present"><img src="' + element.img +'" alt="' + element.title + '"><form action="?action=product" method="POST"><input type="hidden" value="'
                +element.title+'" name="name"><input type="hidden" value="'+element.brand+'" name="seller"><input type="hidden" value="'+element.img+'" name="img"><input type="submit" value="'+price+'&euro;" name="price"></form></div>');
            empty = true;
        });
        if(!empty){
            $('#result-product2').append('<li><span id="product_place">Cet article n\'éxiste pas</span>');
        }
    }

    function bubbleSort(arr){
        var len = arr.length;

        for (var i = len-1; i >=0 ; i--){
            for(var j = 1; j <= i; j++){
                if(parseInt(arr[j-1]['price'], 10) > parseInt(arr[j]['price'], 10)){
                    var temp = arr[j-1];
                    arr[j-1] = arr[j];
                    arr[j] = temp;
                }
            }
        }
        return arr;
    }
});