<style>
    email-body {
        font-family: Avenir, Helvetica, sans-serif;
        box-sizing: border-box;
        background-color: #FFFFFF;
        border-bottom: 1px solid #EDEFF2;
        border-top: 1px solid #EDEFF2;
        margin: 0;
        padding: 0;
        width: 100%;
    }
    h1   {color: blue;}
    p {
        font-family: Avenir, Helvetica, sans-serif;
        box-sizing: border-box;
        color: #74787E;
        font-size: 16px;
        line-height: 1.5em;
        margin-top: 0;
        text-align: left;
    }
</style>
<div class="email-body">
    
    <h1 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #2F3133; font-size: 19px; font-weight: bold; margin-top: 0; text-align: left;">Hello {{ $OperatorMailsend['email'] }},</h1>

    <p>
       You are receiving this email because you have requested for password reset for your <a href="http://hl-events/password/reset">Activate</a> 
    </p>

    <div>
        <p>Please click the button to activate your account
      
        
            <a target="_blank" rel="noopener noreferrer" href="{{ route('eventmie.register_activates', ['activate'=>($OperatorMailsend['email']),'validToken'=>($validToken),'name'=>Str::slug($OperatorMailsend['name'])]) }}" class="button button-blue" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; border-radius: 3px; box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16); color: #FFF; display: inline-block; text-decoration: none; -webkit-text-size-adjust: none; background-color: #3097D1; border-top: 10px solid #3097D1; border-right: 18px solid #3097D1; border-bottom: 10px solid #3097D1; border-left: 18px solid #3097D1;">Activate
            </a>
        </p>
        <p>
            If you’re having trouble clicking the "Activate" button, copy and paste the URL below into your web browser:
        </p>
        <a class="dib" href="{{ route('eventmie.register_activates', ['activate'=>($OperatorMailsend['email']),'validToken'=>($validToken),'name'=>Str::slug($OperatorMailsend['name'])]) }}">
        https://www.holidaylandmark.com/events/register/activate-user/ {{ Str::slug($OperatorMailsend['name']) }}/{{ $OperatorMailsend['email'] }}/{{$validToken}}
        </a>
        <br/><br/>

        <p>
            If you did not request a account activation, no further action is required.
        </p>

    </div>

</div>
<p>
    Regards,<br>
    https://www.holidaylandmark.com/<br>
    <br>
</p>