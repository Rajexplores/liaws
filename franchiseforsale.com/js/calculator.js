function netWorthTab(evt, tabName, clsName) {
    var i, x, tablinks;
    var cls = document.querySelector('.'+clsName);
    x = cls.getElementsByClassName("tabs-panel");
    for (i = 0; i < x.length; i++) {
      x[i].classList.remove('show');
    }
    tablinks = cls.getElementsByClassName("tabs-title");
    for (i = 0; i < x.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" is-active", ""); 
    }
    if(tabName != 'video'){
        if(document.querySelector('video')){
          document.querySelector('video').pause();
        }
     }
    document.getElementById(tabName).classList.add('show');
    evt.currentTarget.className += " is-active";
  }

//Calculate Net Worth
function calculateFloat(id, type){
    var flVal = document.getElementById(id).value;
    var valNew = parseFloat(flVal);
    document.getElementById(id).value = valNew.toFixed(2);
    if(type == 'assets'){
        calculateAssetsLiabilities('asset','AssetTotal');
    }else{
        calculateAssetsLiabilities('liability','LiabilityTotal');
    }
}

function calculateAssetsLiabilities(cls,id){
    var arr = document.getElementsByClassName(cls);
    var tot=0;
    for(var i=0;i<arr.length;i++){
        if(parseFloat(arr[i].value)){
            let temp = parseFloat(arr[i].value);
            tot += temp;
        }
    }
    // console.log(arr);
    document.getElementById(id).value = tot.toFixed(2);
    calculateNetWorth();
}

function calculateNetWorth(){
    var assets = document.getElementById('AssetTotal').value;
    var liabilities = document.getElementById('LiabilityTotal').value;
    var netWorth = assets - liabilities;
    document.getElementById('netWorthTotal').value = netWorth.toFixed(2);
}