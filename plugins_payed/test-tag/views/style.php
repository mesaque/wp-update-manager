<style>
    #result_container {
        display: none;
    }
    
    #result {
        max-height: 250px;
        overflow-y: auto;
        padding: 0 30px;
        width: 25%;
    }
    
    .meter {
        background: none repeat scroll 0 0 #808080;
        border-radius: 25px 25px 25px 25px;
        box-shadow: 0 -1px 1px rgba(255, 255, 255, 0.3) inset;
        height: 20px;
        margin: 20px 0;
        padding: 2px;
        position: relative;
        width: 80%;
        display: none;
    }
    .meter > span {
        background-color: #2BC253;
        background-image: -moz-linear-gradient(center bottom , #2BC253 37%, #54F054 69%);
        border-radius: 20px;
        box-shadow: 0 2px 9px rgba(255, 255, 255, 0.3) inset, 0 -2px 6px rgba(0, 0, 0, 0.4) inset;
        display: block;
        height: 100%;
        overflow: hidden;
        position: relative;
        transition: width 1s ease-out, border-radius 1s ease-out;
    }
    .meter > span:after, .animate > span > span {
        animation: 2s linear 0s normal none infinite move;
        background-image: -moz-linear-gradient(-45deg, rgba(255, 255, 255, 0.2) 25%, rgba(0, 0, 0, 0) 25%, rgba(0, 0, 0, 0) 50%, rgba(255, 255, 255, 0.2) 50%, rgba(255, 255, 255, 0.2) 75%, rgba(0, 0, 0, 0) 75%, rgba(0, 0, 0, 0));
        background-size: 50px 50px;
        border-radius: 20px;
        bottom: 0;
        content: "";
        left: 0;
        overflow: hidden;
        position: absolute;
        right: 0;
        top: 0;
        z-index: 1;
    }
    .meter.noanim > span:after {
        animation: 2s linear 0s normal none 0 move;
    }
    .animate > span:after {
        display: none;
    }
    @-moz-keyframes move {
        0% {     
            background-position: 0 0;
        }
        100% {
            background-position: 50px 50px;
        }
    }
    
    .blue > span {
        background-color: #21759B;
        background-image: -moz-linear-gradient(center top, #21759B, #519ABA);
    }
</style>
