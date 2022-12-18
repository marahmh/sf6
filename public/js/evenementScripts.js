window.onload=()=>{
    const content=document.querySelector("#content");
    const searchInput=document.querySelector("#search");
    const select=document.querySelector("#typeEvenements");
searchInput.addEventListener("keyup", ()=>{
    content.innerHTML="";
sendSearchFilterRequest();
});
    select.addEventListener("change",() =>{
sendSearchFilterRequest();
    })


    function sendSearchFilterRequest() {
        console.log("Here")
        const params= new URLSearchParams();
        params.append("search",searchInput.value);
        sel = select.selectedOptions;
        for (let i=0 ;i<sel.length;i++) {
            params.append("types[]", sel[i].value);
        }
            const url =new URL(window.location.href);
        console.log(url.pathname+"?"+params.toString());
            fetch(url.pathname+"?"+params.toString()+"&ajax=1",{
                headers :{
                    "X-Requested-with":"XMLHttpRequest"
                }
            }).then(response =>response.json()
            ).then(data=>{
                content.innerHTML=data.content;
            }).catch(e=> alert(e)
            );
        }
    
}
