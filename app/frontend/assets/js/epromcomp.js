import { screen } from "../../assets/js/screen.js"

class epromComparator extends screen {

  constructor(app) {
    super("epromcomparator", app);
    this.attach(this.eventRaised);
  }

  eventRaised(v) {
  }

  addEpromData(eprom, epromIndex) {

    let html = "";

    html += `<div class="eprom-container">
                <div class="alert alert-primary" role="alert">
                  <ul>
                    <li>File: ${eprom.file}</li>
                    <li>Type: ${eprom.class} ${eprom.eprom}</li>
                  </ul>
                </div>`;

    eprom.data.available.map((part) => {
      html += `<div class="eprom-param-container" data-epromparam="${part.key}"><span class="eprom-param-name">${part.name}</span><div class="eprom-param-content"></div></div>`;
    });

    html += `</div>`;

    let $html = $(html);

    eprom.data.available.map((part) => {
      const $htmlpart = $html.find(`.eprom-param-container[data-epromparam="${part.key}"]`);
      const param = {
        '_epromData': eprom.data.detail[part.key],
        '_element': $htmlpart,
        'display': function(dispHex) {

          switch(this._epromData.is) {
            case "map":

              var tab = "<table>";
                tab += "<thead><th></th>";
                for(var col = 0; col < this._epromData._p.c; col++) {
                  tab += '<th class="table-map-th">' + this._epromData._p.xaxis[col] + "</th>";
                }
                tab += "</thead>";
                tab += "<tbody>";
                for(var row = 0; row < this._epromData._p.r; row++) {
                  tab += "<tr>";
                    tab += '<th class="table-map-trt">' + this._epromData._p.yaxis[row] + "</th>";
                    for(var col = 0; col < this._epromData._p.c; col++) {

                      let valCell = "";
                      let classtd = [ "table-map-td" ];
                      
                      if (this._epromData.value.readable[row][col] < 0) classtd.push("table-map-td-watch");
                      
                      valCell = dispHex ? this._epromData.value.source[row][col] : Math.abs(this._epromData.value.readable[row][col]);
  
                      tab += '<td class="' + classtd.join(" ") + '">' + valCell + '</td>';
                  }
                  tab += "<tr>";
                }

                tab += "</tbody>"
              tab += "</table>";

              this._element.find(".eprom-param-content").html(tab);
              break;

            case "value":
              this._element.find(".eprom-param-content").html('<span class="eprom-param-value">' + (dispHex ? this._epromData.value.source : this._epromData.value.readable) + '</span>');
              break;
          }

        }
      };
      $htmlpart.data("eprom_param", )
      param.display(false);
    });

    $(this.container).find(`#accordionEpromComparator .eprom-container[data-eprom="${epromIndex}"]`).append($html);

  }

  loadEprom(file) {

    const nbEpromLoaded = $(this.container).find(".eprom-container").length;

    $(this.container).find("#accordionEpromComparator").append(`<div class="eprom-container" data-eprom="${nbEpromLoaded}" ></div>`);

    this.api.getEpromData(file, (json) => { this.addEpromData(json, nbEpromLoaded); });

  }

  buildHTML() {
  
    const html = '<div class="accordion" id="accordionEpromComparator"></div>';
    $(this.container).html(html);

    this.loadEprom("M305AEA7.BIN");
    this.loadEprom("JWT-90-TT-AT-370.bin");
    this.loadEprom("AshSpec-BA-1.BIN");
    
  }

  buildHTMLAdapter(adapter) {

    let adapterInfo = adapter.versions.product_name;
    let rocTemp = 0;

    if (adapter.hw.roc_temperature) {
      rocTemp = adapter.hw.roc_temperature.celsius;
    }

    if (rocTemp) {
      adapterInfo += `<span class="badge badge-size bg-primary">${rocTemp}°c</span>`;
    }

    if (adapter._data) {
      if (adapter._data.temp == "danger") {
        adapterInfo = `<i class="bi bi-thermometer-high temperatore-sensor-high temperatore-sensor-icon"></i>` + adapterInfo;
      }
      else if (adapter._data.temp == "warning") {
        adapterInfo = `<i class="bi bi-thermometer-half temperatore-sensor-warning temperatore-sensor-icon"></i>` + adapterInfo;
      }
    }

    let html = '<div class="accordion" id="accordionOverview">';
    
    html += `<div class="accordion-item">

      <h4 class="accordion-header accordion-adapter d-flex" id="headingadapter">
        <div class="button-adapter collapsed flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseadapter" aria-expanded="false" aria-controls="collapseadapter">
          <div class="row adapterinfo">
            <div class="col-auto me-auto"><i class="bi bi-gpu-card icon-card"></i> ${adapterInfo}</div>
          </div>
        </div>
      </h4>

      <div id="collapseadapter" class="accordion-collapse collapse" aria-labelledby="headingadapter">
        <div class="accordion-body">              
        </div>
      </div>

    </div>`;

    html += '</div>';

    return html;

  }

}

export { epromComparator };