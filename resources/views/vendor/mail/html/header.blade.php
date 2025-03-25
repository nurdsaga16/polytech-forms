@props(['url'])
<tr>
    <td class="header">
        <a href="http://localhost:5173  " style="display: inline-block;">
            @if (trim($slot) === 'Polytech Forms')
                <img src="https://i.postimg.cc/fysBGkzk/logo-polytech-forms-v2.png" class="logo"
                     alt="Polytech Forms Logo">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
