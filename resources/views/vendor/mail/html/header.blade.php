@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="48" height="48" style="display:block;margin:0 auto 8px">
<rect width="32" height="32" rx="7" fill="#4f46e5"/>
<text x="16" y="22" font-family="system-ui, sans-serif" font-size="15" font-weight="700" text-anchor="middle" fill="#fff">LJ</text>
</svg>
<span style="color:#18181b;font-size:19px;font-weight:bold;text-decoration:none">{{ config('app.name') }}</span>
</a>
</td>
</tr>
