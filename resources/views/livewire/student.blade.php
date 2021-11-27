<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        課題管理
    </h2>
</x-slot>
<div class="py-12">
<div class="max-w-7x1 mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-x1 sm rounded-lg px-4 py-4">
            <table class="table-fixed w-full">
                <thead>
                    <tr class="bg-gray-1000">
                        <th class="px-4 py-2">課題名</th>
                        <th class="px-4 py-2">提出</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kadais as $kadai)
                        <tr>
                            <td class="border px-4 py-2">{{ $kadai->name }}</td>
                            <td class="border px-4 py-2">
                                @switch($kadai->status)
                                    @case(0)
                                        未提出
                                        @break
                                    @case(1)
                                        済
                                        @break
                                    @case(2)
                                        不要
                                        @break
                                    @default
                                        未提出
                                        @break
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
