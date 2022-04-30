<div style="direction: rtl;float: right">
    <table class="table table-sm" style="width: 100%;">
        <thead>
        <tr>
            <th style="border: 1px solid #dddddd;padding: 8px;">نام و نام خانوادگی</th>
            <th style="border: 1px solid #dddddd;padding: 8px;">شماره تماس</th>
            <th style="border: 1px solid #dddddd;padding: 8px;">توضیحات ایده</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td style="border: 1px solid #dddddd;padding: 8px;">
                    {{$user->full_name}}
                </td>
                <td style="border: 1px solid #dddddd;padding: 8px;">
                    {{$user->phone_number}}
                </td>
                <td style="border: 1px solid #dddddd;padding: 8px;">
                    {{$user->description}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
