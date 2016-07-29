function addComma(val) {
    while (/(\d+)(\d{3})/.test(val.toString())) {
        val = val.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
    }
    return val;
}

String.prototype.trunc = String.prototype.trunc ||
    function(n) {
        return (this.length > n) ? this.substr(0, n - 1) + '&hellip;' : this;
    };

function clearStorage() {
    localStorage.clear();
};

function updateDate(d) {
    var originalDate = d;
    newDate = originalDate.replace(/(\d\d\d\d)(\d\d)(\d\d)/g, '$2/$3/$1');
    $("#showDate").html(newDate);
};

function getHealthcheckDates() {
    $.getJSON("/data/filters.php", function(obj) {
        $.each(obj.data, function(key, value) {
            //console.log(value.date);
            var originalDate = value.date;
            var visibleDate = originalDate.replace(/(\d\d\d\d)(\d\d)(\d\d)/g, '$2/$3/$1');
            $("#filterDate").append("<option value='"+value.date+"'>"+visibleDate+"</option>");
            
        });
    })
}

function abbreviateNumber(n) {
    with (Math) {
        var base = floor(log(abs(n))/log(1000));
        var suffix = 'KMB'[base-1];
        return suffix ? String(n/pow(1000,base)).substring(0,5)+suffix : ''+n;
    }
}

function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
};

function getEDR(importid) {

    $.getJSON("/data/edr.php?date="+importid, function(data) {
        $(".eventTotalEDR").html(data.total[0].clients);
    });
}

function buildList(src, target, importid, country) {

    localStorage.setItem("totalActualUnique", "0");
    localStorage.setItem("totalHealth", "0");
    localStorage.setItem("totalNetwork", "0");
    localStorage.setItem("totalUnique", "0");
    localStorage.setItem("totalPotential", "0");
    localStorage.setItem("usNetworkTarget","44");
    localStorage.setItem("germanyNetworkTarget","20");
    localStorage.setItem("franceNetworkTarget","20");
    localStorage.setItem("ukNetworkTarget","20");

    var networkTarget = localStorage.getItem(country+"NetworkTarget"); 

    $(".networkTarget").html(networkTarget+"%")


    $(target).append("<li id='summary' class='list-group-item'>" + "<span class='clientname'>Total</span>" + "<div class='item-columns pull-right'>" + "<span class='left-align totalUnique'>0</span>" + "<span class='mobile totalMobile'>0.00</span>" + "<span class='desktop totalDesktop'>0.00</span>" + "<span class='health totalHealth'>--</span>" + "<span class='network totalNetwork'>--</span>" + "<span class='potential totalPotential'>--</span>" + "</div>" + "</li>");
            $(".totalUnique").html("--");
            $(".totalNetwork").html("--");
            $(".totalHealth").html("--");
            $(".totalDesktop").html("--");
            $(".totalMobile").html("--");
            $(".totalPotential").html("--");

            $(".eventTotalClients").html("--");
            $(".eventCurrency").html("--");
            $(".eventQuantity").html("--");
            $(".eventPrice").html("--");
            $(".eventOrderTotal").html("--");
            $(".eventProductName").html("--");
            $(".eventProductID").html("--");
            $(".eventProductIDMatch").html("--");

    $.getJSON("/data/totalhealth.php?importid=" + importid, function(data) {
            $.each(data, function(b, v) {
                localStorage.setItem("eventTotalClients", v.clients.totalclients);
                localStorage.setItem("eventCurrency", v.pixelhealth.currency);
                localStorage.setItem("eventQuantity", v.pixelhealth.quantity);
                localStorage.setItem("eventPrice", v.pixelhealth.price);
                localStorage.setItem("eventOrderTotal", v.pixelhealth.ordertotal);
                localStorage.setItem("eventProductName", v.pixelhealth.productname);
                localStorage.setItem("eventProductID", v.pixelhealth.productid);
                localStorage.setItem("eventProductIDMatch", v.pixelhealth.productidmatch);
            });
            var cnvt;
            $(".eventTotalClients").html(localStorage.getItem("eventTotalClients"));
            cnvt = (localStorage.getItem("eventCurrency")/localStorage.getItem("eventTotalClients"))*100;
            $(".eventCurrency").html(cnvt.toFixed(2) + "%");
            cnvt = (localStorage.getItem("eventQuantity")/localStorage.getItem("eventTotalClients"))*100;
            $(".eventQuantity").html(cnvt.toFixed(2) + "%");
            cnvt = (localStorage.getItem("eventPrice")/localStorage.getItem("eventTotalClients"))*100;
            $(".eventPrice").html(cnvt.toFixed(2) + "%");
            cnvt = (localStorage.getItem("eventOrderTotal")/localStorage.getItem("eventTotalClients"))*100;
            $(".eventOrderTotal").html(cnvt.toFixed(2) + "%");
            cnvt = (localStorage.getItem("eventProductName")/localStorage.getItem("eventTotalClients"))*100;
            $(".eventProductName").html(cnvt.toFixed(2) + "%");
            cnvt = (localStorage.getItem("eventProductID")/localStorage.getItem("eventTotalClients"))*100;
            $(".eventProductID").html(cnvt.toFixed(2) + "%");
            cnvt = (localStorage.getItem("eventProductIDMatch")/localStorage.getItem("eventTotalClients"))*100;
            $(".eventProductIDMatch").html(cnvt.toFixed(2) + "%");

    });

    $.getJSON(src, function(obj) {
        var country = obj.country;
        country = country.toUpperCase();
        $(".country").html(country);

        var totalUnique         = 0;
        var totalClients        = 0;
        var totalBVClients      = 0;
        var totalActualUnique   = 0;
        var totalUniqueTarget   = 0;
        var totalHealth         = 0;
        var totalNetwork        = 0;
        var totalHealthCt       = 0;
        var totalPotential      = 0;
        var rollID              = 1;
        var promises            = [];

        var popoverOptions = {
            "html": true,
            "container": "body",
            "placement": "right"
        };



        $.getJSON("/data/potentialcheck.php?country=" + country, function(data) {

            var average = data.totals.average;
            //console.log(average);

            $.each(data.targets, function(b, v) {
                //(($monthly/$average)*7/7)*10;
                var x = (v.monthlyvisitors / average);
                var y = 14
                var potential = (x * (y / y)) * 10;
                $("#company" + v.id + " span.potential").html(potential.toFixed(2));
                totalPotential = totalPotential + potential;
                localStorage.setItem("totalPotential", totalPotential);
                $(".totalPotential").html(totalPotential.toFixed(2));
            });
        });



        $.each(obj.list, function(key, value) {

            //promises.push(value.id);
            //<span tabindex='0' role='button' data-toggle='popover' data-trigger='focus' title='What is this?' data-content='" + v.info + "' class='glyphicon glyphicon-question-sign'></span>

            $(target).append("<li id='company" + value.id + "' class='list-group-item'>" 
                + "<span class='clientname' tabindex='0' role='button' title='Health Check' data-toggle='popover' data-trigger='focus' data-content='Not Installed'>" + (value.company).trunc(18) + "</span>" 
                + "<div class='item-columns pull-right'>" 
                + "<span class='left-align'>" + addComma(value.monthlyvisitors) + "</span>" 
                + "<span class='mobile'>0</span>" + "<span class='desktop'>0</span>" 
                + "<span class='health'>0</span>" + "<span class='network'>0</span>" 
                + "<span class='potential'>0</span>" + "</div>" + "</li>")

            $('[data-toggle="popover"]').popover(popoverOptions);
            
            totalClients = +totalClients + 1;
            totalUnique = +totalUnique + +value.monthlyvisitors;
            localStorage.setItem("totalClients",totalClients);
            localStorage.setItem("totalUnique", totalUnique);
            localStorage.setItem("totalUniqueTarget", totalUnique*.8);
            localStorage.setItem("totalClientsTarget", totalClients*.8);
            


            $.getJSON("/data/clientmatch.php?client=" + value.id, function(data) {
                $.each(data.match, function(b, v) {
                    //console.log(v.bvID);

                    //console.log(value.id + " : " + v.bvID)

                    $.getJSON("/data/edr.php?date=" + importid, function(data) {
                        $.each(data.edr, function(b1, v1) {
                            if (v1.client == v.bvID) {
                                console.log(v1.client);
                                $("#company"+value.id+" .clientname").addClass("edr");
                            };
                        });

                        $(".edr").css("color","red").css("font-weight","bold");
                    });

                    $.ajax({
                        type: "POST",
                        data: "importid=" + importid + "&bvid=" + v.bvID + "&clientid=" + value.id + "&analytics=" + value.analytics,
                        url: "/data/healthcheck.php",
                        success: function(n) {

                            //console.log("http://loc.bvpixel/data/healthcheck.php?importid=" + importid + "&bvid=" + v.bvID + "&clientid=" + value.id + "&analytics=" + value.analytics);

                            var buildTip = "";

                            totalActualUnique = +totalActualUnique + +value.monthlyvisitors;
                            localStorage.setItem("totalActualUnique", totalActualUnique);

                            totalBVClients = +totalBVClients + 1;
                            localStorage.setItem("totalBVClients", totalBVClients);

                            // Refactor to read a mobile BV Pixel data
                            //console.log(n.data.health);
                            var desktopHealth = n.data.desktop;
                            var mobileHealth = n.data.mobile;

                            // Composite Score
                            var composite = (desktopHealth * .5) + (mobileHealth * .5);

                            $("#company" + value.id + " span.desktop").html(desktopHealth);
                            $("#company" + value.id + " span.mobile").html(mobileHealth);
                            $("#company" + value.id + " span.health").html(composite);
                            $("#company" + value.id + " span.network").html(n.data.network);

                            

                            if (composite < 12 && composite >= 0) {
                                $("#company" + value.id).addClass("warning");
                            };
                            if (composite >= 24) {
                                $("#company" + value.id).addClass("success");
                            };

                            totalHealth = +totalHealth + +composite;
                            totalHealthCt = totalHealthCt + 1;

                            totalNetwork = +totalNetwork + +n.data.network;

                            // Store
                            localStorage.setItem("totalHealth", totalHealth);
                            localStorage.setItem("totalNetwork", totalNetwork);


                            $(".totalNetwork").html(totalNetwork.toFixed(2));
                            var totalNetworkPercent = (totalNetwork*.1).toFixed(2);
                            $(".totalNetworkPercent").html(totalNetworkPercent+"%");

                            
                            var createNum = totalNetwork*.1
                            //console.log(networkTarget);
                            //console.log(createNum);
                            //var networkProgress = parseInt(networkTarget.toFixed(2)) - parseInt(createNum.toFixed(2));
                            //console.log(networkProgress);

                            // var networkTarget = localStorage.getItem(country+"NetworkTarget"); 

                            var totalNetworkGoal = parseInt(totalNetworkPercent)/parseInt(networkTarget);
                            //console.log("totalNetworkPercent: " + totalNetworkPercent);
                            //console.log("networkTarget: "+ networkTarget);

                            $(".totalNetworkGoal").html((totalNetworkGoal*100).toFixed(1) + "%");

                            $(".totalActualUnique").html(abbreviateNumber(totalActualUnique));

                            var totalUniqueTarget = localStorage.getItem("totalUniqueTarget");
                            var totalUniqueTargetGoal = totalActualUnique/totalUniqueTarget;
                            $(".totalUniqueTargetGoal").html((totalUniqueTargetGoal*100).toFixed(1) + "%");

                            buildTip = "<div class='col-sm-12' style='font-size:90%'><h3>Desktop</h3>"
                                     + "<span class='exists"+n.data.pixelhealth.currency+"'> Currency: " + n.data.pixelhealth.currency + "</span><br>"
                                     + "<span class='exists"+n.data.pixelhealth.quantity+"'> Quantity: " + n.data.pixelhealth.quantity + "</span><br>"
                                     + "<span class='exists"+n.data.pixelhealth.price+"'> Price: " + n.data.pixelhealth.price + "</span><br>"
                                     + "<span class='exists"+n.data.pixelhealth.productname+"'> Product Name: " + n.data.pixelhealth.productname + "</span><br>"
                                     + "<span class='exists"+n.data.pixelhealth.productid+"'> Product ID: " + n.data.pixelhealth.productid + "</span><br>"
                                     + "<span class='exists"+n.data.pixelhealth.ordertotal+"'> Order Total: " + n.data.pixelhealth.ordertotal + "</span><br>"
                                     + "<span class='exists"+n.data.pixelhealth.productidmatch+"'> Product ID Match: "+ n.data.pixelhealth.productidmatch + "</span>"
                                     + "</div>"
                                     + "<div class='col-sm-12' style='font-size:90%; padding-bottom:20px'><h3>Mobile</h3>"
                                     + "<span class='exists"+n.data.pixelhealth.currency_m+"'> Currency: " + n.data.pixelhealth.currency_m + "</span><br>"
                                     + "<span class='exists"+n.data.pixelhealth.quantity_m+"'> Quantity: " + n.data.pixelhealth.quantity_m + "</span><br>"
                                     + "<span class='exists"+n.data.pixelhealth.price_m+"'> Price: " + n.data.pixelhealth.price_m + "</span><br>"
                                     + "<span class='exists"+n.data.pixelhealth.productname_m+"'> Product Name: " + n.data.pixelhealth.productname_m + "</span><br>"
                                     + "<span class='exists"+n.data.pixelhealth.productid_m+"'> Product ID: " + n.data.pixelhealth.productid_m + "</span><br>"
                                     + "<span class='exists"+n.data.pixelhealth.ordertotal_m+"'> Order Total: " + n.data.pixelhealth.ordertotal_m + "</span><br>"
                                     + "<span class='exists"+n.data.pixelhealth.productidmatch_m+"'> Product ID Match: "+ n.data.pixelhealth.productidmatch_m + "</span>"
                                     + "</div>"

                            $("#company" + value.id + " span.clientname").attr('data-content',buildTip).data('bs.popover').setContent();
                            
                        }

                    })
                });
            })
        })

        $.when.apply($, promises).done(function() {

            var totalActualUnique = localStorage.getItem("totalActualUnique");
            
            var totalBVClients    = localStorage.getItem("totalBVClients");
            var totalClientsTarget = localStorage.getItem("totalClientsTarget");
            var totalHealth = localStorage.getItem("totalHealth");
            var totalNetwork = localStorage.getItem("totalNetwork");
            var totalPotential = localStorage.getItem("totalPotential");
            var totalUniqueTarget = localStorage.getItem("totalUniqueTarget");

            var totalNetworkPercent = (totalNetwork*.1).toFixed(2);
            
            var totalClientGoal = totalBVClients/totalClients;


            $(".totalUnique").html(addComma(totalUnique));
            $(".totalActualUnique").html(abbreviateNumber(totalActualUnique));
            $(".totalUniqueTarget").html(abbreviateNumber(totalUniqueTarget));
            

            $(".totalClients").html(totalClients);
            $(".totalBVClients").html(totalBVClients);
            $(".totalClientTarget").html(Math.round(totalClientsTarget));
            $(".totalClientGoal").html((totalClientGoal*100).toFixed(1) + "%");



            $
            //console.log(totalUniqueTarget);
            



        });

    })
}
