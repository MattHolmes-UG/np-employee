// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Area Chart Example
// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var areaChart = document.getElementById("areaChart");
var chart_btn = document.getElementById("chart-link");
var dash_btn = document.getElementById("dash-link");
var table_btn = document.getElementById("table-link");
var breadcrumb = document.getElementById("active-breadcrumb");
var icon_cards = document.getElementById("icon-cards");
var table = document.getElementById("e_table");
var other_charts = document.getElementById("other-charts");
var pie = document.getElementById("myDoughnutChart");
var bar = document.getElementById("myBarChart");
var salaryCard = document.getElementById("salaryCard");
var qualifications = document.querySelectorAll(".qualifications");
var e_names = document.querySelectorAll(".e-names");
var e_qual = document.querySelectorAll(".e-qual");
var salary = document.querySelectorAll(".salary");
var dob = document.querySelectorAll(".dob");
var id = document.querySelectorAll(".id");
var date_joined = document.querySelectorAll(".date_joined");
var eName = [];
var eQual = [];
var salaries = [];
var dobs = [];
var dates_joined = [];
var ids = [];
var Quals = [];
var qualification_qtn = [];

for (let i = 0; i < e_names.length; i++) {
    const name = e_names[i].innerHTML;
    const eqln = e_qual[i].innerHTML;
    eName.push(name);
    eQual.push(eqln);
}
for (let i = 0; i < qualifications.length; i++) {
    const qual = qualifications[i];
    Quals.push([qual.innerHTML, Number(qual.value)]);
}
Quals = Quals.sort((a, b) => { return b[1] - a[1] }).map(arr => arr[0]);
eQual = eQual.map(st => ` ${st} `);
for (let i = 0; i < salary.length; i++) {
    const isal = salary[i].innerHTML.replace("$", "");
    salaries.push(isal);
}
salaries = salaries.map(sal => Number(sal));

//Calc and display total salary
const totalSalary = salaries.reduce((total, sal) => { return total + sal }, 0);
salaryCard.innerText = `Total Salary: $${totalSalary}`;
console.log(totalSalary);

// To get the numbers of employees with a particular qualification
Quals.forEach(qual => {
    let len = eQual.filter(a => a === qual).length;
    qualification_qtn.push(len);
});

for (let i = 0; i < dob.length; i++) {
    const date = dob[i].innerHTML;
    dobs.push(date);
}
for (let i = 0; i < date_joined.length; i++) {
    const date = date_joined[i].innerHTML;
    dates_joined.push(date);
}
for (let i = 0; i < id.length; i++) {
    const num = Number(id[i].innerHTML);
    ids.push(num);
}

var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        xLabels: eName,//["Mar 1", "Mar 2", "Mar 3", "Mar 4", "Mar 5", "Mar 6", "Mar 7", "Mar 8", "Mar 9", "Mar 10", "Mar 11", "Mar 12", "Mar 13"],
        yLabels: Quals,
        datasets: [{
            label: 'Qualification',
            lineTension: 0.3,
            backgroundColor: "rgba(2,117,216,0.2)",
            borderColor: "rgba(2,117,216,1)",
            pointRadius: 5,
            pointBackgroundColor: "rgba(2,117,216,1)",
            pointBorderColor: "rgba(255,255,255,0.8)",
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(2,117,216,1)",
            pointHitRadius: 50,
            pointBorderWidth: 2,
            data: eQual//[10000, 30162, 26263, 18394, 18287, 28682, 31274, 33259, 25849, 24159, 32651, 31984, 38451],
        }],
    },
    options: {
        scales: {
            xAxes: [{
                time: {
                    unit: 'date'
                },
                gridLines: {
                    display: false
                },
                // ticks: {
                //   // maxTicksLimit: 7
                // }
            }],
            yAxes: [{
                type: "category",
                ticks: {
                    min: "NCE",
                    max: "PhD",
                    maxTicksLimit: 8
                },
                gridLines: {
                    color: "rgba(0, 0, 0, .125)",
                }
            }],
        },
        legend: {
            display: false
        }
    }
});

//Bar chart for Qualification
var myLineChart = new Chart(bar, {
    type: 'bar',
    data: {
        labels: eName,
        datasets: [{
            label: '',
            backgroundColor: "rgba(2,117,216,1)",
            borderColor: "rgba(2,117,216,1)",
            data: salaries,
        }],
    },
    options: {
        scales: {
            xAxes: [{
                // time: {
                //   unit: 'month'
                // },
                gridLines: {
                    display: false
                },
                ticks: {
                    maxTicksLimit: 10
                }
            }],
            yAxes: [{
                ticks: {
                    min: 0,
                    max: 500000,
                    maxTicksLimit: 10
                },
                gridLines: {
                    display: true
                }
            }],
        },
        legend: {
            display: false
        }
    }
});
var myDoughnutChart = new Chart(pie, {
    type: 'pie',
    data: {
        datasets: [{
            data: qualification_qtn,
            backgroundColor: ["red", "blue", "green", "pink", "purple", "orange", "gray"]
        }],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: Quals
    },
});

function showUpdateModal(id) {
    const e_id = ids.findIndex( i => i === id );
    let names = eName[e_id].split(" ");
    const firstname = names[0];
    const lastname = names[1];
    const qual = eQual[e_id];
    const salary = salaries[e_id];
    const dob = dobs[e_id];
    const dateJoined = dates_joined[e_id];

    console.log(qual)

    for (let i = 0; i < qualifications.length; i++) {
        // console.log(qualifications[i].innerHTML);
        console.log(Quals)
        if (qualifications[i].innerHTML===qual) {
            qualifications[i].setAttribute("selected", "selected");
        }
    }

    $('#uID').val(id);
    $('#ufirstName').val(firstname);
    $('#ulastName').val(lastname);
    $('#usalary').val(salary);
    $('#udob').val(dob);
    $('#udateJoined').val(dateJoined);

    $('#updateModal').modal('show');
    console.log(names[1]);
}

function showDeleteModal(id) {
    $('#dID').val(id);
    $('#deleteModal').modal('show');
}


function showCharts() {
    chart_btn.classList.add('active');
    dash_btn.classList.remove('active');
    table_btn.classList.remove('active');
    areaChart.style.display = "block";
    other_charts.style.display = "block";
    icon_cards.style.display = "none";
    table.style.display = "none";
    breadcrumb.innerText = "Charts";
}
function showTables() {
    chart_btn.classList.remove('active');
    dash_btn.classList.remove('active');
    table_btn.classList.add('active');
    icon_cards.style.display = "none";
    areaChart.style.display = "none";
    other_charts.style.display = "none";
    table.style.display = "block";
    breadcrumb.innerText = "Tables";
}
function showDash() {
    chart_btn.classList.remove('active');
    dash_btn.classList.add('active');
    table_btn.classList.remove('active');
    icon_cards.style.display = "block";
    areaChart.style.display = "block";
    other_charts.style.display = "none";
    table.style.display = "block";
    breadcrumb.innerText = "Overview";
}
