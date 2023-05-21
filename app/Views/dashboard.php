<?= $this->extend('body_layout'); ?>
<?= $this->section('style'); ?>
<style>
    .owl-nav {
        font-size: 2em;
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .owl-nav button span {
        background-color: #dbab81 !important;
        color: white;
        padding: 10px !important;
        border-radius: 5px;
    }

    .my-card {
        background: rgba(255, 255, 255, 0.17);
        border-radius: 16px;
        backdrop-filter: blur(6.1px);
        -webkit-backdrop-filter: blur(6.1px);
        border: 1px solid rgba(255, 255, 255, 0.88);
    }
</style>
<?= $this->endSection('style'); ?>

<?= $this->section('content'); ?>
<?= $this->include('components/_loading'); ?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h2 class="m-0 d-flex align-items-center gap-2"><i class="ti ti-cloud-search text-danger" style="font-size: 36px;"></i> Info Cuaca</h2>
            </div>
            <div class="card-body">
                <div class="card shadow-none border border-2 rounded">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="selectType" class="form-label">Pilih Pencarian</label>
                                <select name="" id="selectType" class="form-select" onchange="getType()">
                                    <option value="">- Pilih Type -</option>
                                    <option value="1">Kapal</option>
                                    <option value="2">Pelabuhan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row d-none" id="type">
                            <div class="col-md-6" id="kapal">
                                <label for="selectKapal" class="form-label">Kapal</label>
                                <select id="selectKapal" class="form-select">
                                    <option value="" selected disabled>- Pilih Kapal -</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="pelabuhan">
                                <label for="selectPelabuhan" class="form-label">Pelabuhan</label>
                                <select id="selectPelabuhan" class="form-select">
                                    <option value="" selected disabled>- Pilih Pelabuhan -</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="result" class="d-none">
                    <div class="card">
                        <div class="card-body rounded" style="background-color: #ffcda1;">
                            <div class="d-flex align-items-center gap-3">
                                <img src="" alt="" id="img-country" style="height: 50px;" class="shadow-sm">
                                <div>
                                    <h2 id="name" class="m-0"></h2>
                                    <p id="coord" class="m-0"></p>
                                </div>
                            </div>
                            <div id="container-carousel"></div>
                            <div class="table-responsive">
                                <table class="table my-card" style="border-radius: 0;">
                                    <caption>Prediksi Cuaca <a href="https://openweathermap.org/" class="text-dark"><u>Open Weather</u></a> <i class="ti ti-sun-wind" style="font-size: 1.3em;"></i></caption>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kondisi Cuaca</th>
                                            <th>Suhu</th>
                                            <th>Kecepatan Angin</th>
                                            <th style="white-space: nowrap;">Arah Angin (U<i class="ti ti-arrow-narrow-up"></i>)</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('content'); ?>

<?= $this->section('script'); ?>
<script>
    var dataKapal = [];

    var getType = () => {
        var select = $("#selectType").val()
        var type = $("#type");
        var kapal = $("#kapal");
        var pelabuhan = $("#pelabuhan");

        switch (select) {
            case "1":
                type.removeClass('d-none')
                kapal.removeClass('d-none')
                pelabuhan.addClass('d-none')
                break;
            case "2":
                type.removeClass('d-none')
                kapal.addClass('d-none')
                pelabuhan.removeClass('d-none')
                break;

            default:
                type.addClass('d-none')
                kapal.removeClass('d-none')
                pelabuhan.removeClass('d-none')
                break;
        }
    }

    var getKapal = () => {
        $.ajax({
            url: "https://www.vms.web.id/vmap/api/get.php",
            data: {
                user: "intern",
                pass: "magang2023",
                type: 1,
                d: 1,
                h: 12
            },
        }).done((kapal) => {
            if (kapal.data.length > 0) {
                $.each(kapal.data, (key, val) => {
                    $("#selectKapal").append(`
                        <option value="${key}" data-latitude="${val.lat}" data-longitude="${val.lon}">${val.date} - ${val.name}</option>
                    `)
                })

                renderKapal()
                $(".load").remove()
            }
        })
    }

    var renderKapal = () => {
        $("#selectKapal").select2({
            placeholder: "Cari berdasarkan kapal..",
            allowClear: true,
            theme: 'bootstrap-5'
        });
        $("#selectKapal").on("select2:select", function(e) {
            var selectedOption = e.params.data.element.dataset;
            search(selectedOption)
        });
    }

    getKapal()
    getPelabuhan()

    var search = (dataThrow) => {
        var form_caption = $("#form-caption")
        var table = $('tbody')
        var latitude = dataThrow.latitude
        var longitude = dataThrow.longitude

        if (latitude != "" && longitude != "") {
            $.ajax({
                url: 'https://api.openweathermap.org/data/2.5/forecast',
                data: {
                    lat: latitude,
                    lon: longitude,
                    appid: 'aad03470310d4af3bdb4d0bfb79761d8',
                }
            }).done(function(data) {
                var container = "";
                var tableRow = "";
                var city = data.city;
                var weather = data.list;

                $("#container-carousel").html('<div id="carousel" class="owl-carousel mt-3"></div>')
                $("#img-country").attr('src', `https://flagcdn.com/w320/${city.country.toLowerCase()}.png`)

                $.ajax({
                    url: `https://restcountries.com/v3.1/alpha/${city.country.toLowerCase()}`
                }).done(function(country) {
                    $("#name").text(`${city.name}, ${country[0].name.common}`)
                    $("#coord").text(`Lat: ${city.coord.lat}, Long: ${city.coord.lon}`)
                });

                $.each(weather, (key, val) => {
                    container += `
                    <div class="card my-card">
                        <div class="card-body p-1">
                            <div class="d-flex align-items-center mb-2">
                                <img src="https://openweathermap.org/img/wn/${val.weather[0].icon}@2x.png" style="width: 40px;">
                                <p class="m-0 me-2" style="font-size: 1.2em;">${val.weather[0].description}</p>
                            </div>
                            <div class="d-flex mb-2 justify-content-between">
                                <div class="temp ms-2">
                                    <span>Temp</span>
                                    <p style="font-size: 1.3em;" class="m-0">${(val.main.temp - 273.15).toFixed(2)}&deg;C</p>
                                </div>
                                <div class="wind ms-2">
                                    <span>Wind</span>
                                    <p style="font-size: 1.3em;" class="m-0">${val.wind.speed}m/s</p>
                                </div>
                                <div class="direction ms-2">
                                    <span>Direction</span>
                                    <div class="d-flex" style="font-size: 1.3em; ">
                                        <p class="m-0">${val.wind.deg}&deg;</p>
                                        <p class="m-0" style="transform: rotate(${val.wind.deg}deg);">
                                            <i class="ti ti-arrow-narrow-up"></i>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="time ms-2">
                                <span>Time</span>
                                <p class="me-2">${val.dt_txt} WIB</p>
                            </div>
                        </div>
                    </div>
                    `;
                    tableRow += `
                    <tr style="font-size: 1.1em;">
                        <td class="align-middle text-center">${key + 1}</td>
                        <td class="align-middle">
                            <img src="https://openweathermap.org/img/wn/${val.weather[0].icon}@2x.png" style="width: 40px;">
                            ${val.weather[0].description}
                        </td>
                        <td class="align-middle">${(val.main.temp - 273.15).toFixed(2)}&deg;C</td>
                        <td class="align-middle">${val.wind.speed}m/s</td>
                        <td class="align-middle">
                            <div class="d-flex justify-content-evenly">
                                <p class="m-0">${val.wind.deg}&deg;</p>
                                <p class="m-0" style="transform: rotate(${val.wind.deg}deg);">
                                    <i class="ti ti-arrow-narrow-up"></i>
                                </p>
                            </div>
                        </td>
                        <td class="align-middle">${val.dt_txt} WIB</td>
                    </tr>
                    `
                });

                $("#carousel").empty();
                $("table tbody").empty();
                var owlcarousel = $(".owl-carousel")
                owlcarousel.append(container)
                $("#carousel").owlCarousel({
                    margin: 10,
                    nav: true,
                    responsiveClass: true,
                    autoWidth: true
                });

                $("table tbody").append(tableRow)
                $("#result").removeClass("d-none")
            });
        } else {
            form_caption.addClass('text-danger')
            form_caption.text('Harap lengkapi form diatas')
        }
    }
</script>
<?= $this->endSection('script'); ?>