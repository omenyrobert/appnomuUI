<style>
    #link{
        text-decoration: none;
        color: #1d9ff0;
        font-size: 14px;
    }
    #link2{
        text-decoration: none; 
    }
.accordion {
  color: #fff;
  cursor: pointer;
  padding: 5x;
  width: 90%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
  background-color: #04273d;
  margin-left: 10px;
  margin-top: 15px;
}

.accordion:after {
  content: '\2192';
  color: #fff;
  font-weight: bold;
  float: right;
  margin-left: 1px;
}

.active:after {
  content: "\A71C";
}

.panel {
  padding: 0 5px;
  font-size: 12px;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
  background-color: #04273d;
}
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <!-- Bootstrap Font Icon CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">


<div style="background-color: #04273d;  padding: 15px; height: 400vh;">
    <a style="color: #fff; text-decoration: none; font-size: 20px;" href="dashboard.php">Dashboard</a>
    <br/>
<br/>
<button class="accordion">
    <i style="color: #fff;" class="bi bi-wallet-fill"></i>&nbsp;&nbsp;&nbsp;Withdraw</button>
  <div class="panel" style="background-color: #04273d; margin-left: 30px; margin-top: 5px;">
  <a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;All Withdraw</a><br/><br/>
  <a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;My Withdraws</a><br/><br/>
  <a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;Make Withdraw</a><br/><br/>
</div>

<button class="accordion">
<i style="color: #fff;" class="bi bi-droplet-fill"></i>&nbsp;&nbsp;&nbsp;Airtime & Utilities</button>
<div class="panel" style="background-color: #04273d; margin-left: 30px; margin-top: 5px;">
  <a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;Buy Airtime</a><br/><br/>
  <a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;Pay Water Bill</a><br/><br/>
  <a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;Pay Electricity Bill</a><br/><br/>
</div>

<button class="accordion">
<i style="color: #fff;" class="bi bi-piggy-bank-fill"></i>&nbsp;&nbsp;&nbsp;Savings</button>
<div class="panel" style="background-color: #04273d; margin-left: 30px; margin-top: 5px;">
  <a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;Saving Categories</a><br/><br/>
  <a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;Saving Sub Categories</a><br/><br/>
  <a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;Enter Saving</a><br/><br/>
</div>
<button class="accordion">
    <i style="color: #fff;" class="bi bi-cash"></i>&nbsp;&nbsp;&nbsp;Loans</button>
  <div class="panel" style="background-color: #04273d; margin-left: 30px; margin-top: 5px;">
  <a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;Personal Loans</a><br/><br/>
  <a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;Soma Loans</a><br/><br/>
  <a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;Business Loans</a><br/><br/>
  <a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;Loans Categories</a><br/><br/>
<a href="" style="color: #1d9ff0;" id="link"><i style="color: #1d9ff0;" class="bi bi-check2-circle"></i>&nbsp;&nbsp;&nbsp;Loans sub Categories</a>
</div>

<hr style="color: #fff;">

<button class="accordion">
<i style="color: #fff;" class="bi bi-headset"></i>&nbsp;&nbsp;&nbsp;Support</button>

<button class="accordion">
<i style="color: #fff;" class="bi bi-chat-dots-fill"></i>&nbsp;&nbsp;&nbsp;Messaging</button>

<button class="accordion">
<i style="color: #fff;" class="bi bi-chat-dots-fill"></i>&nbsp;&nbsp;&nbsp;All Sent SMS</button>

<button class="accordion">
<i style="color: #fff;" class="bi bi-chat-dots-fill"></i>&nbsp;&nbsp;&nbsp;Send Bulk SMS</button>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>