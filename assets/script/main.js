$(function() {
    $("#form").on('submit',function(event){
        event.preventDefault();
        const data = $(this).serialize();

        $(this).hide();
        $('.main').append('<h2 class = "response text-center">loading...</h2>')
        
        axios.post('/src/action/form.php',data).then(response=>{
            const result = response.data;
            if(result == true){
                $('.response').addClass('text-success');
                $('.response').text('Success');
            }else{
                $('.response').addClass('text-danger');
                $('.response').text('Something went wrong');
            }

            setTimeout(function(){
                $('.response').remove();
                $('#form').show();
            },5000);
        });
    });
});