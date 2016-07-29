$(function() {

	clearStorage();

    var list = getUrlVars()["list"];
    var inputdate = getUrlVars()["date"];

    getHealthcheckDates();
    updateDate(inputdate);

    buildList("/data/data.php?list="+list, "#top100", inputdate, list);
    getEDR(inputdate);

    $("#filterAction").click(function(){
        var getDate     = $( "#filterDate option:selected" ).val();
        var getCountry  = $( "#filterCountry option:selected" ).val();

        window.location.href="/?list="+getCountry+"&date="+getDate; 
    });

});
