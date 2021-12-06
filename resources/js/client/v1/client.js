window.CoinEmbed = {};
window.CoinEmbed.widgetsInstances = [];
window.CoinEmbed.apiURL = 'https://coinembed.com/api';
window.CoinEmbed.frameSourceURL = 'https://coinembed.com/frame';

window.CoinEmbed.documentLoaded = function(event){
    window.CoinEmbed.loadDocumentInstances();
};

window.CoinEmbed.loadDocumentInstances = function(){
    let CoinEmbedDocumentInstances = document.querySelectorAll("[rel='coinembed-widget']");
    CoinEmbedDocumentInstances.forEach(function(currentValue, currentIndex, listObj){
        let widgetInstance = new window.CoinEmbed.WidgetInstance(currentValue);
        widgetInstance.render();
        window.CoinEmbed.widgetsInstances.push(widgetInstance);
    });
};

window.CoinEmbed.WidgetInstance = function(element) {
    this.element = element;
    this.instanceId = Math.random().toString(36).substr(2, 9);
    this.widgetId = element.getAttribute('data-ce-id');
    this.afterSubmissionCallback = element.getAttribute('data-ce-callback');
    let frameSourceURL = window.CoinEmbed.frameSourceURL;
    let apiURL = window.CoinEmbed.apiURL;
    if(element.getAttribute('data-ce-frame-source-url')){
        frameSourceURL = element.getAttribute('data-ce-frame-source-url');
    }
    if(element.getAttribute('data-ce-api-url')){
        apiURL = element.getAttribute('data-ce-api-url');
    }
    // Set widget details
    let amount = null;
    let currency = null;
    let productName = null;
    let productImageUrl = null;
    if(element.getAttribute('data-ce-api-url')){
        apiURL = element.getAttribute('data-ce-api-url');
    }
    if(element.getAttribute('data-ce-amount')){
        amount = element.getAttribute('data-ce-amount');
    }
    if(element.getAttribute('data-ce-currency')){
        currency = element.getAttribute('data-ce-currency');
    }
    if(element.getAttribute('data-ce-name')){
        productName = element.getAttribute('data-ce-name');
    }
    if(element.getAttribute('data-ce-image')){
        productImageUrl = encodeURI(element.getAttribute('data-ce-image'));
    }
    this.render = function(){
        this.element.innerHTML = '<iframe src="'+frameSourceURL+'/v1/widget/'+this.widgetId+'?instanceId='+this.instanceId+'&api_url='+apiURL+'&amount='+amount+'&currency='+currency+'&productName='+productName+'&productImageUrl='+productImageUrl+'" width="310" height="360" frameborder="0" scrolling="no" allowtransparency="true"></iframe>';
    };
    this.success = function(data){
        let afterSubmissionCallback = this.afterSubmissionCallback;
        window[afterSubmissionCallback](data);
    };
};

window.CoinEmbed.findWidgetInstance = function(instanceId){
    for(var i=0;i<=window.CoinEmbed.widgetsInstances.length;i++){
        let instance = window.CoinEmbed.widgetsInstances[i];
        if(typeof instance !== "undefined"){
            if(instance.instanceId == instanceId){
                return instance;
            }
        }
    }
    return null;
};

window.addEventListener('coinembed-reinitiate', function(event) {
    console.log('coinembed-reinitiate');
    window.CoinEmbed.loadDocumentInstances();
});

window.addEventListener('message', function(event) {
    if(event.data.hasOwnProperty('CoinEmbed')){
        let widgetInstance = window.CoinEmbed.findWidgetInstance(event.data.CoinEmbed.id);
        widgetInstance.success(event.data.CoinEmbed.token);
    }
});


// On Window Load, Call window.CoinEmbed.documentLoaded()
if(window.attachEvent) {
    window.attachEvent('onload', window.CoinEmbed.documentLoaded);
} else {
    if(window.onload) {
        var currentWindowOnLoad = window.onload;
        var newWindowOnLoad = function(evt) {
            currentWindowOnLoad(evt);
            window.CoinEmbed.documentLoaded(evt);
        };
        window.onload = newWindowOnLoad;
    } else {
        window.onload = window.CoinEmbed.documentLoaded;
    }
}
