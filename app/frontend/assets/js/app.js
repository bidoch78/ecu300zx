import { api } from "../../assets/js/api.js"
import { myEvent, myEventSubscriber } from "../../assets/js/myevent.js"
// import { login } from "../../assets/js/login.js"
import { screen } from "../../assets/js/screen.js"
import { epromComparator } from "../../assets/js/epromcomp.js"

class app_core {

  #api = null;
  #container = null;
  #loadingInPrgDiv = null;
  #event = new myEvent({ 'adapterId': -1 });
  #screen = null;
//     #login = null;

  #appName = "ECU Reader";

  constructor(container) {

    this.#api = new api(this);
    this.#container = container;
    if (!container) {
        console.log("app container not found");
    }
    screen._defaultModalTitle = this.#appName;

//        this.#login = new login(this);
//        this.#login.container = $("body");

    $("head title").text(this.#appName);
    this.loadingInProgress(true);
    this.buildHTML();
    this.loadData();

  }

  get api() { return this.#api; }
  get event() { return this.#event; }

  showError(msg) {

    const $container = $('body');
    var $divError = $container.find(".toast-error-container");
    if ($divError.length == 0) {

      $divError = $(`<div aria-live="polite" aria-atomic="true" style="width:100%" class="toast-error-container position-absolute top-0">
                      <div class="toast-container position-absolute top-0 end-0 p-3"></div>
                    </div>`);
      $($container).append($divError);

    }
    
    var newError = `<div class="toast mb-1" role="alert" aria-live="assertive" aria-atomic="true">
                      <div class="toast-header">
                        <span class="badge bg-danger"><i class="bi bi-bell"></i></span>
                        <strong class="me-auto ms-1">Error</strong>
                        <!--small class="text-muted">2 seconds ago</small-->
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                      </div>
                      <div class="toast-body">` + msg + `</div>
                    </div>`;
    
    $divError.find(".toast-container").prepend(newError);

    new bootstrap.Toast($divError.find(".toast")[0], { 'delay': 10000, 'autohide': true }).show();

  }

  loadingInProgress(status) {

      if (status) {
          
          if (this.#loadingInPrgDiv) return;

          this.#loadingInPrgDiv = document.createElement("div");
          this.#loadingInPrgDiv.classList.add("loading_data");
          this.#loadingInPrgDiv.innerHTML = `<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>`;
          
          document.body.append(this.#loadingInPrgDiv);

      }
      else {

          if (!this.#loadingInPrgDiv) return;
          this.#loadingInPrgDiv.remove();
          this.#loadingInPrgDiv = null;

      }

  }

  buildHTML() {

    var curURLScreen = window.location.hash;
    if (curURLScreen) curURLScreen = curURLScreen.substring(1);
    switch(curURLScreen) {
//         case "virtualdrives":
//         case "virtualdrives":  
//         case "physicaldrives":
//         case "cfgforeign":
//         case "patrol":
//         case "bbu":
//         case "satadrivers":
//           break;
    default:
      curURLScreen = "epromcomparator"
    }

    const app_screen = `<div class="row app_div">

                            <div class="col-md-12 app_container">
                            
                              <nav class="navbar header navbar-expand-lg bg-body-tertiary">
                              
                                <div class="container-fluid">
                                  <img class="navbar-image-logo" src="/assets/images/EPROM_chip_under_100KB.png" alt="Bootstrap" width="30" height="24">
                                  <a class="navbar-brand appname" href="#">ECUReader</a>
                                  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="headerDropDown" aria-controls="headerDropDown" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                  </button>
                                  <div class="collapse navbar-collapse" id="headerDropDown">
      
                                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">                                                
                                      <li class="nav-item">
                                        <a class="nav-link disabled ` + (curURLScreen == "epromcomparator" ? "active" : "") + `" data-screen="epromcomparator" href="#">Eprom Comparator</a>
                                      </li>
                                    </ul>
                                    <div class="form-check form-switch">
                                      <input class="form-check-input" type="checkbox" id="debug_switch" role="switch">
                                      <label class="form-check-label" for="debug_switch">Debug</label>
                                    </div>

                                  </div>
                                </div>
                              </nav>

                              <div class="screen_div p-2"></div>

                            </div>

                            <div class="col-md-4 debug_div">
                              <div class="accordion">
                              </div>
                            </div>

                          </div>

                          <nav class="navbar footer fixed-bottom bg-body-tertiary">
                            <div class="col-md-12">
                              <p class="text-center placeholder-glow">
                                <span class="placeholder "><span class="appname">ECUReader</span></span>
                              </p>
                              <p class="text-center placeholder-glow">
                                <span class="placeholder"><span class="appcopyright">#########</span> <span class="appversion">######</span></span>
                              </p>
                            </div>
                          </nav>`;

    $(this.#container).append(app_screen);
    $(".appname").html(this.#appName);

//         $(this.#container).find(".header #debug_switch").on("change", $.proxy(function(e) {
//             const $currentItem = $(e.currentTarget);
//             app_core.createCookie("debug_mode", $currentItem.is(":checked"));
//             if ($currentItem.is(":checked")) {
//               $(this.#container).find(".app_container").removeClass("col-md-12").addClass("col-md-8");
//               $(this.#container).find(".debug_div").show();
//             }
//             else {
//               $(this.#container).find(".app_container").removeClass("col-md-8").addClass("col-md-12");
//               $(this.#container).find(".debug_div").hide();
//             }
//             return false;
//         }, this));        

//         $(this.#container).find(".header .nav-link").on("click", $.proxy(function(e) {
//             const $currentItem = $(e.currentTarget);
//             $currentItem.closest(".navbar-nav").find(".nav-link").removeClass("active");
//             $currentItem.addClass("active");
//             this.goToScreen(this.getCurrentScreen());
//             return false;
//         }, this));

    if (app_core.readCookie("debug_mode") == "true") {
      $(this.#container).find(".header #debug_switch").prop("checked", true).trigger("change");
    }

  }

//     showLogin(options) {
//       this.#login.buildHTML(options);
//     }

  loadData() {

    this.#api.getVersion($.proxy(function(json) {

      const $footer = $(this.#container).find(".footer");

      $footer.find(".appversion").html(json.api);
      $footer.find(".appcopyright").html(json.copyright);

      $footer.find(".placeholder").removeClass("placeholder");        

      this.loadingInProgress(false);
      
      this.goToScreen(this.getCurrentScreen());

    }, this));

  }

  addTraces(json) {

    if (json && json.traces) {

      const $debugDiv = $(this.#container).find(".debug_div");
      var countTraces = $debugDiv.find(".trace-item").length;

      for(var i = 0; i < json.traces.length; i++) {

        countTraces++;
        var htmlValue = json.traces[i].return.replaceAll("\n", "<br>") + "<br>";

        var $html = $(`<div class="accordion-item trace-item">
          <h2 class="accordion-header" id="accordeonItemH${countTraces}">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#accordeonItem${countTraces}" aria-expanded="true" aria-controls="accordeonItem${countTraces}">
              <i class="bi bi-arrows-expand"></i> ${json.traces[i].cmd}
            </button>
          </h2>
          <div id="accordeonItem${countTraces}" class="accordion-collapse collapse" aria-labelledby="accordeonItemH${countTraces}">
            <div class="accordion-body">
              ${htmlValue}
            </div>
          </div>
        </div>`);

        $html.find(".accordion-button").on("click", function() {
          var $this = $(this);
          if ($this.attr("aria-expanded") == "true") {
            $this.find(".bi").removeClass("bi-arrows-expand").addClass("bi-arrows-collapse");
          }
          else {
            $this.find(".bi").removeClass("bi-arrows-collapse").addClass("bi-arrows-expand");
          }
        });

        $debugDiv.append($html);

      }
    }

  }

  getCurrentScreen() {
    return $(this.#container).find(".header .nav-link.active").attr("data-screen");
  }

  goToScreen(screen) {

    if (this.#screen && this.#screen.name == screen) return;

    this.loadingInProgress(true); 
    
    if (this.#screen) { this.#screen.destroy(); this.#screen = null; }

    switch(screen) {
      case "epromcomparator": this.#screen = new epromComparator(this); break;        
    }

    window.location.hash = "#" + screen;

    var screenContainer = this.#container.getElementsByClassName("screen_div")[0];

    if (!this.#screen) {
      this.showError("screen `" + screen + "` not found !")
      screenContainer.innerHTML = "";
    }
    else {
      this.#screen.container = screenContainer;
      this.#screen.buildHTML();  
    }

    this.loadingInProgress(false); 

  }

  static createCookie(name, value, days) {
    if (days) {
      var date = new Date();
      date.setTime(date.getTime()+(days*24*60*60*1000));
      var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
  }

  static readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
      var c = ca[i];
      while (c.charAt(0)==' ') c = c.substring(1,c.length);
      if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
  }

  static eraseCookie(name) {
    app_core.createCookie(name,"",-1);
  }
    
}

export { app_core };