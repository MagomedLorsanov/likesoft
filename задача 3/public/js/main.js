$(document).ready(function () {
    let btnList = $(".bttn-list");

    $(document).on("click", ".bttn", () =>{
        let allbtns = btnList.find(".bttn").toArray();
        let shiftedBtn = allbtns.shift();
        allbtns.push(shiftedBtn);
        btnList.empty();
        allbtns.forEach(btn => {
            btnList.append(btn);
        });
    });

})