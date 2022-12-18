
 function  fetchQuiz(){
     $("button").click(function(){
         console.log(window.location)
         $.get(window.location, function(data, status){
            renderQuiz(data)
         });
     });

 }


$(document).ready(function() {




function  renderQuiz(data){
    console.log(data)
    }

});