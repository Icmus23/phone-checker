$(document).ready(function(){
    function checkPhone() {
        $.get('/api/1.0/get_phone_status', function(response) {
            console.log(response);

            $('#result').html(response.status + ". " + getCurrentDateTime());
        }, 'json');
    }

    function getCurrentDateTime() {
        var currentdate = new Date();
        var datetime = "Последнее обновление:" + currentdate.getDate() + "."
                        + (currentdate.getMonth()+1)  + "."
                        + currentdate.getFullYear() + ", "
                        + currentdate.getHours() + ":"
                        + currentdate.getMinutes() + ":"
                        + currentdate.getSeconds();
        return datetime;
    }

    setInterval(checkPhone, 60000);
});