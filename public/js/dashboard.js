$(document).ready(function(){
    loadGraphData();
    // inItGraph(daysOfMonth);
});

function loadGraphData(){
    let monthDay = [];
    $.ajax({
        method: 'GET',
        url: baseUrl + '/getGraphData',
        data: {},
        success : function(response,textStatus,xhr){
            if(xhr.status == 200){
                response = JSON.parse(response);
                // console.log(typeof response);
                var enquiries = response.data.totalEnquiries;
                var replies = response.data.totalReplies;
                if(response.data){
                    fillTabsData(response.data);
                }

                inItGraph(enquiries,replies); // intialize graph

            }else{
                return false;
            }
        },
        error: function(jqXHR,textStatus,errorThrown){
            console.log(jqXHR + ' ' + errorThrown);
        }        
    });
    
}

function inItGraph(monthData,replies){
    /** Chart of enquiry and reply */
    Highcharts.chart('enquiryGraph', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Monthly Enquiry and Response'
        },
        xAxis: {
            categories: Object.keys(monthData).map(function (key) {
                return key;
            }),
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Days'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr>'+
                            '<td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.f}</b></td>'+
                            '</tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Enquiries',
            data: Object.keys(monthData).map(function (key) {
                return monthData[key];
            })
    
        }, {
            name: 'Replies',
            data: Object.keys(replies).map(function (key) {
                return monthData[key];
            })
        }]
    });
}

function fillTabsData(data){
    document.getElementById('totalVisitors').innerText = data.totalVisitors;
    document.getElementById('totalSubscribers').innerText = data.totalSubs;
    document.getElementById('totalEnquiries').innerText = data.allEnquiries;
    document.getElementById('totalReplies').innerText = data.allReplies;
}