document.addEventListener('DOMContentLoaded', function() {
    let events = [];
    var calendarEl = document.getElementById('content');
    fetch( "http://127.0.0.1:8000/evenement/getEventsForUser",{
        headers :{
            "X-Requested-with":"XMLHttpRequest"
        }
    }).then(response =>response.json()
    ).then(data=>{
        console.log(data)
        for (let i=0 ;i<data.length;i++) {

            events.push({title:data[i]['titreEvenement'], start :data[i]['dateEvenement'],allday:true})

        }
        console.log(events)
        var calendar = new FullCalendar.Calendar(calendarEl, {
            events:events,
            initialView: 'dayGridMonth',
            locale:'fr'
        });
        calendar.render();
    }) .catch(e=> alert(e)
    );



});


