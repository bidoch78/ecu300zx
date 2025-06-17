import { screen } from "../../assets/js/screen.js"

class epromComparator extends screen {

  constructor(app) {
    super("epromcomparator", app);
    this.attach($.proxy(this.eventRaised, this));
  }

  eventRaised(v) {
    
    if (v.name == "switch_diff") this.compareEprom(v.value);
    if (v.name == "switch_hexa") {

      $(this.container).find(`#accordionEpromComparator .eprom-container .eprom-param-container`).each((indexd, data) => {
        $(data).attr("data-disp", v.value ? "hex": "value");
        $(data).data("eprom_param").refresh();
      });

    }

  }

  addEpromData(eprom, epromIndex) {

    let html = "";

    html += `<div class="eprom-container-data">
                <div class="alert alert-primary" role="alert">
                  <ul>
                    <li>File: ${eprom.file}</li>
                    <li>Type: ${eprom.class} ${eprom.eprom}</li>
                  </ul>
                </div>`;

    eprom.data.available.map((part) => {
      //html += `<div class="eprom-param-container" data-disp="hex" data-epromparam="${part.key}"><span class="eprom-param-name">${part.name}</span><div class="eprom-param-content"></div></div>`;
      html += `<div class="eprom-param-container" data-disp="` + (this.app.showHexaSwitch() ? "hex": "value") + `" data-epromparam="${part.key}"><span class="eprom-param-name">${part.name}</span><div class="eprom-param-content"></div></div>`;
    });

    html += `</div>`;

    let $html = $(html);

    eprom.data.available.map((part) => {

      $html.find(`.eprom-param-container[data-epromparam="${part.key}"]`).each((index, item) => {

        const $htmlpart = $(item);

        const param = {
          '_epromData': eprom.data.detail[part.key],
          '_element': $htmlpart,
          '_gradient': {
            'colourGradientor': function(p, rgb_beginning, rgb_end) {
              var w = p * 2 - 1;
              var w1 = (w + 1) / 2.0;
              var w2 = 1 - w1;
              var rgb = [parseInt(rgb_beginning[0] * w1 + rgb_end[0] * w2),
                          parseInt(rgb_beginning[1] * w1 + rgb_end[1] * w2),
                          parseInt(rgb_beginning[2] * w1 + rgb_end[2] * w2)];
              return rgb;
            },
            'colorBlue': [ 50, 130, 246],
            'colorBlueLight': [ 66, 190, 246],
            'colorGreen': [ 117, 250, 97 ],
            'colorYellow': [ 255, 253, 85 ],
            'colorRed': [235, 51, 36],
            'getColor': function(c) {
              switch(c) {
                case "g": return this.colorGreen;
                case "b": return this.colorBlue;
                case "bl": return this.colorBlueLight;
                case "y": return this.colorYellow;
                case "r": return this.colorRed;
              }
              return [255, 255, 255];
            },
            'get': function(val, def) {

              if (!def.length) return [255, 255, 255];
              if (val < def[0].v) return this.getColor(def[0].c)

              for(var i = 0; i < def.length - 1; i++) {
                const colfrom = def[i];
                const colto = def[i+1];
                if (val >= colfrom.v && val < colto.v && (colto.v - colfrom.v)) {
                  const pct = (val - colfrom.v) / (colto.v - colfrom.v);
                  return this.colourGradientor(pct, this.getColor(colto.c), this.getColor(colfrom.c));
                }
              }

              return this.getColor(def[def.length-1].c);

            }
          },
          'display': function(dispHex) {

            switch(this._epromData.is) {
              case "map":

                var indexData = 0;
                var tab = "<table>";
                  tab += "<thead><th></th>";
                  for(var col = 0; col < this._epromData._p.c; col++) {
                    tab += '<th class="eprom-data table-map-th" data-index="' + (indexData++) + '">' + this._epromData._p.xaxis[col] + "</th>";
                  }
                  tab += "</thead>";
                  tab += "<tbody>";
                  for(var row = 0; row < this._epromData._p.r; row++) {
                    tab += "<tr>";
                      tab += '<th class="eprom-data table-map-trt" data-index="' + (indexData++) + '">' + this._epromData._p.yaxis[row] + "</th>";
                      for(var col = 0; col < this._epromData._p.c; col++) {

                        let valCell = "";
                        let classtd = [ "eprom-data", "table-map-td" ];
                        let style = [];
                        
                        if (this._epromData.value.readable[row][col] < 0) classtd.push("table-map-td-watch");
                        
                        if (this._epromData._p.gradient) {
                          style.push('background-color: rgb(' + this._gradient.get(Math.abs(this._epromData.value.readable[row][col]), this._epromData._p.gradient) + ')');
                        }

                        valCell = dispHex ? this._epromData.value.source[row][col] : Math.abs(this._epromData.value.readable[row][col]);

                        tab += '<td style="' + style.join(";") + '" class="' + classtd.join(" ") + '" data-index="' + (indexData++) + '">' + valCell + '</td>';
                    }
                    tab += "</tr>";
                  }

                  tab += "</tbody>"
                tab += "</table>";

                this._element.find(".eprom-param-content").html(tab);
                break;
              
              case "text":
                this._element.find(".eprom-param-content").html('<span class="eprom-param-value eprom-data" data-index="0">' + (dispHex ? this._epromData.value.source.join(" ") : this._epromData.value.readable) + '</span>');
                break;

              case "value":
                this._element.find(".eprom-param-content").html('<span class="eprom-param-value eprom-data" data-index="0">' + (dispHex ? this._epromData.value.source : this._epromData.value.readable) + '</span>');
                break;
            }

          },
          'refresh': function() {
            this.display(this._element.attr("data-disp") == "hex");
          }
        };
        $htmlpart.data("eprom_param", param);
        param.refresh();

      });

    });

    $(this.container).find(`#accordionEpromComparator .eprom-container[data-eprom="${epromIndex}"]`).append($html);

  }

  loadEprom(file) {

    const nbEpromLoaded = $(this.container).find(".eprom-container").length;

    $(this.container).find("#accordionEpromComparator").append(`<div class="eprom-container" data-eprom="${nbEpromLoaded}" ></div>`);

    this.api.getEpromData(file, (json) => { this.addEpromData(json, nbEpromLoaded); });

  }

  compareEprom(show) {

    $(this.container).find(".eprom-container .eprom-data").removeClass("eprom_diff_data");

    if (show !== true) return;

    let compEprom = null;
    $(this.container).find(".eprom-container").each((index, eprom) => {

      const $eprom = $(eprom);
      if (!index) { compEprom = $(eprom); return; }

      compEprom.find(".eprom-param-container").each((indexp, param) => {

        const $param = $(param);
        const paramName = $param.attr("data-epromparam");

        //Check all data
        $param.find(".eprom-data").each((indexd, data) => {
          
          const $data = $(data);
          const dataIndex = $data.attr("data-index");
          
          const $dataToComp = $eprom.find('.eprom-param-container[data-epromparam="' + paramName + '"] .eprom-data[data-index="' + dataIndex + '"]')

          if ($data.text() != $dataToComp.text()) $dataToComp.addClass("eprom_diff_data");

        });

      });

    });

  }

  buildHTML() {
  
    const html = '<div class="accordion" id="accordionEpromComparator"></div>';
    $(this.container).html(html);

    this.loadEprom("M305AEA7.BIN");
    this.loadEprom("JWT-90-TT-AT-370.bin");
    this.loadEprom("AshSpec-BA-1.BIN");

    setTimeout(() => {
        this.compareEprom(this.app.showDiffSwitch());
    }, 500);
    
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