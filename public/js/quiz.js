 nbQ=1,nbChoix=0;
 data={}
 questions=[]

 function addQuestionToArray(){
     question={}
     choix=[]
     for(let i=1;i<=nbChoix;i++){
         console.log()
        c={
            "text": $("#input"+i).val(),
            "etat":$("#checkBox"+i).is(":checked")
        }
         choix.push(c)
     }
question={
        "question":$('#question').val(),
        "choix":choix
}
questions.push(question)
 }
 function submitQuiz(){
quiz= {
        "titre": $('#titreQuiz').val(),
        "Description":$('#descriptionQuiz').val()
    }
     data = {
         "quiz": quiz
         ,
         "questions": questions
     }
     console.log(data)
     $.ajax({
         type: "POST",
         url: "http://127.0.0.1:8000/quiz/add/5",
         data: JSON.stringify(data),
         success: function (){
             console.log("cv")
         },
         dataType: "json"
     });

 }
$(document).ready(function() {

    $('#nvChoix').click(function() {
        nbChoix++
        console.log("Choix "+nbChoix)
        $('#choixquestion').append(
        $(document.createElement('input')).prop({
            type: 'checkbox',
            id: 'checkbox'+nbChoix,
            className: 'form-control'
        }).add(
            $(document.createElement('input')).prop({
                type: 'text',
                id: 'input'+nbChoix,
                className: 'form-control'
            })


        ))
    })

    $('#nvQuestion').click(function() {

       if ($('#question').val()==="" || nbChoix==0  ){
           alert("Vous n'avez pas saissi une question")
           return
       }
        addQuestionToArray()
        $('#question').val("")
        nbQ++
        nbChoix=0;
       $('#choixquestion').html("")

    })
    $('#submit').click(function() {
        addQuestionToArray()
        submitQuiz()
    })


});
