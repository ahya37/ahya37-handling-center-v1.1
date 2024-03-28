<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
<style>
    .grid-container {
        display: grid;
        grid-template-columns: auto auto auto auto;
        margin-top: -1px;
    }
    .grid-container > div {
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.8);
        text-align: center;
    }
    .polygon {
        width: 100px !important;
        margin-left: -121px !important;
        margin-top: -2px !important;
		
    }
</style>
</head>

<body>
    <table>
        @foreach ($card_tags as $i)
        <tr>
            @foreach ($i['tags'] as $item)
            <td>
                <table>
                    <tr>
                        @foreach ($item as $row)
                        <td>
                            <div style="background-color: rgba(255, 255, 255, 0.8);
                            border: 1px solid rgba(0, 0, 0, 0.8);
                            text-align: center;">
                              <div style="width: 219px; height: 325px; background-color: #f08619; margin-bottom:2px;">
                                  <div style="
                                  width: 200px;
                                  height: 318px;
                                  background-color: #fefefe;
                                  padding-left: 2px;
                                  position: absolute;
                                  margin-left: 8px;
                                  margin-top: 2px;
                                  margin-bottom: -5px;
                                ">
                                          <img src="{{asset('/assets/images/tag/polygontop.png')}}" class="polygon" />
                                      <div>
                                        <p style="
                                      font-family: Impact, sans-serif;
                                      font-size: 17px;
                                      margin-top: -106px;
                                      color: #fefefe;
                                      padding: 3px;
                                      font-weight: bold;
                                      margin-left: -139px;
                                    ">
                                              NO URUT
                                          </p>
                                          <p style="
                                      font-family: sans-serif;
                                      font-size: 30px;
                                      margin-top: -28px;
                                      padding: 3px;
                                      font-style: normal;
                                      margin-left: -160px;
                                      color: #fefefe;
                                    ">
                                              {{$row->no_urut}}
                                          </p>
                                          <p style="
                                      font-family: Sans-serif;
                                      margin-left: 50px;
                                      margin-right: 5px;
                                      margin-top: -60px;
                                      font-size: 15px;
                                      font-weight: bold;
                                      text-align: right;
                                      margin-bottom: 18px;
                                    ">
                                             {{$label}}
                                          </p>
                                          <p style="
                                      font-family: Sans-serif;
                                      margin-left: 60px;
                                      margin-right: 5px;
                                      font-size: 11px;
                                      font-weight: bold;
                                      margin-top: -19px;
                                    ">
                                              <b>{{ $tag->group_date }}</b>
                                          </p>
                                      </div>
                    
                                      <div style="
                                            width: 100px;
                                            background-color: #f08619;
                                            height: 30px;
                                            margin-left: 46px;
                                            border: #f08619;
                                            border-width: 100px solid #f08619; 
                                            border-style: solid;
                                            box-sizing: border-box;
                                            border-radius: 15px;
                                          ">
                                          <img src="{{asset('/storage/'.$row->foto_jamaah)}}" style="width: 100px; height:130px; border-radius: 15px; margin-top:-99px" />
                                      </div>
                                      <p style="text-align: center; font-family: Sans-serif; font-weight: bold; margin-top: 6px;">
                                        {{$row->nama_jamaah}}
                                      </p>
                                      <p style="text-align: center; font-family:Sans-serif; font-size: 10px; font-weight: bold; margin-top: -12px;">
                                         Room Type : {{strtoupper($row->room_jamaah ?? '') }}
                                      </p> <p style="text-align: center; font-family:Sans-serif; font-size: 10px; font-weight: bold; margin-top: -9px;">
                                          Contact Person TL : {{$tl->telp_jamaah ?? '' }}
                                      </p>
                                  </div>
                              </div>
                            </div>
                        </td>
                        @endforeach
                    </tr>
                </table>
            </td>
            @endforeach   
        </tr>
        @endforeach
    </table>
</body>

</html>
