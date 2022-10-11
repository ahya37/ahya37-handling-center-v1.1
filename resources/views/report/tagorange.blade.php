<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
</head>
<style>
    .grid-container {
        display: inline-grid;
        grid-template-columns: auto auto auto auto;
        gap: 5px;
        margin-top: -1px;
    }

    .grid-container>div {
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.8);
        text-align: center;
    }

    .polygon {
        width: 100px !important;
        margin-left: -120px !important;
    }
</style>

<body>
    <div class="grid-container">
        @foreach ($data as $item)
            <div style="width: 219px; height: 325px; background-color: #f08619">
                <div
                    style="
            width: 200px;
            height: 310px;
            background-color: #fefefe;
            padding-left: 2px;
            position: absolute;
            margin-left: 8px;
            margin-top: 5px;
            margin-bottom: -5px;
          ">
                    <div>
                        <img src="/assets/images/tag/polygontop.svg" class="polygon" />
                        <p
                            style="
                font-family: impact;
                font-size: 20px;
                margin-top: -110px;
                color: #fefefe;
                padding: 3px;
                font-weight: normal;
                margin-left: -140px;
              ">
                            NO URUT
                        </p>
                        <p
                            style="
                font-family: sans-serif;
                font-size: 30px;
                margin-top: -28px;
                padding: 3px;
                font-style: normal;
                margin-left: -160px;
                color: #fefefe;
              ">
                            {{ $item->no_urut }}
                        </p>
                        <p
                            style="
                font-family: Monospace;
                margin-left: 80px;
                margin-top: -60px;
                font-size: 17px;
                font-weight: bold;
              ">
                            UMRAH GRUP
                        </p>
                        <p
                            style="
                font-family: Sans-serif;
                margin-left: 30px;
                font-size: 12px;
                font-weight: bold;
                margin-top: -19px;
              ">
                            <b>{{ $tag->group_date }}</b>
                        </p>
                    </div>

                    <div
                        style="
              width: 120px;
              background-color: #fefefe;
              height: 150px;
              margin-left: 38px;
              border: #f08619;
              border-width: 100px solid #f08619;
              border-style: solid;
              box-sizing: border-box;
              border-radius: 15px;
              margin-top: 20px;
            ">
                        <img src="{{asset('/storage/'.$item->foto_jamaah)}}"
                            style="width: 100%; height: 100%; border-radius: 15px" />
                    </div>
                    <p style="text-align: center; font-family: Sans-serif; font-weight; font-weight: bold;">
                        {{ $item->nama_jamaah }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</body>

</html>
