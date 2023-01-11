<x-slot name="header">
    <h2 class="text-xl text-gray-800 leading-tight">
        管理者画面
    </h2>
</x-slot>
<div class="py-12">
    @if(Auth::user()->role != 1)
    <div class="max-w-7x1 mx-auto sm:px-6 lg:px-8 text-red-500">
        <div class="bg-white overflow-hidden shadow-x1 sm rounded-lg px-4 py-4">
            閲覧権限がないページです。
        </div>
    </div>
    @else
    <div class="max-w-7x1 mx-auto sm:px-6 lg:px-8">
        <form method="POST" action="/admin/import" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="file" name="file" id="file" class="form-control">
            <button type="submit" class="bg-indigo-500 hover bg-indigo-700 text-white font-bold py-2 px-4 rounded my-3">アップロード</button>
        </form>
        <form method="get" action="/admin/export">
            {{ csrf_field() }}
            <select name="year" class="form-control">
                <option value="1">1年生</option>
                <option value="2">2年生</option>
                <option value="3">3年生</option>
            </select>
            <button type="submit" class="bg-indigo-500 hover bg-indigo-700 text-white font-bold py-2 px-4 rounded my-3">ダウンロード</button>
        </form>
        
        <div class="bg-white overflow-hidden shadow-x1 sm rounded-lg px-4 py-4">
            @if(session()->has('message'))
                <div class="bg-teal-100 border-lt-4 border teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            <button wire:click="createKadai()" class="bg-indigo-500 hover bg-indigo-700 text-white font-bold py-2 px-4 rounded my-3">新規課題</button>
            @if($isOpen)
            @include('livewire.create-kadai')
            @endif
            @if($isConfirmOpen)
            @include('livewire.delete-confirm')
            @endif
            @if($isStudentConfirmOpen)
            @include('livewire.delete-student-confirm')
            @endif
            <table class="table-fixed w-full border">
                <thead>
                    <tr class="bg-gray-1000">
                        <th class="px-4 py2 w-20 text-xs w-1/6 sm:text-base">@sortablelink('id', 'ID')</th>
                        <th class="px-4 py-2 text-left text-xs sm:text-base">@sortablelink('name', '課題名')</th>
                        <th class="px-4 py-2 text-left text-xs sm:text-base">@sortablelink('target', '対象学年')</th>
                        <th class="px-4 py-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kadais as $kadai)
                        <tr v-for="row in rows" class="bg-white odd:bg-gray-200">
                            <td class="px-4 py-2 text-xs sm:text-base text-center">{{ $kadai->id }}</td>
                            <td class="px-4 py-2 text-xs sm:text-base">{{ $kadai->name }}</td>
                            <td class="px-4 py-2 text-xs sm:text-base">{{ $kadai->target . '年生' }}</td>
                            <td class="px-1 py-2 text-xs sm:text-base text-right">
                                <button wire:click="editKadai({{ $kadai->id }})" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-2 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button wire:click="deleteConfirm({{ $kadai->id }}, '{{ $kadai->name }}')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-4">
            <form method="get" action="/admin/downloadLog">
                {{ csrf_field() }}
                <button type="submit" class="bg-indigo-500 hover bg-indigo-700 text-white font-bold py-2 px-4 rounded my-3">生徒ログイン履歴ダウンロード</button>
            </form>
        </div>
        <div class="flex flex-wrap mt-5" id="tabs-id">
            <div class="w-full">
                <ul class="flex mb-0 list-none flex-wrap pt-3 pb-4 flex-row">
                    <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                        <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-white bg-indigo-600 cursor-pointer" onclick="changeAtiveTab(event,'tab-one')">
                        1年生
                        </a>
                    </li>
                    <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                        <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-indigo-600 bg-white cursor-pointer" onclick="changeAtiveTab(event,'tab-two')">
                        2年生
                        </a>
                    </li>
                    <li class="-mb-px mr-2 last:mr-0 flex-auto text-center">
                        <a class="text-xs font-bold uppercase px-5 py-3 shadow-lg rounded block leading-normal text-indigo-600 bg-white cursor-pointer" onclick="changeAtiveTab(event,'tab-three')">
                        3年生
                        </a>
                    </li>
                </ul>
                <div class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                    <div class="px-4 py-5 flex-auto">
                        <div class="tab-content tab-space">
                            <div class="block" id="tab-one">
                                <p>
                                    <button wire:click="deleteStudentsConfirm(1)" class="bg-red-500 hover bg-red-700 text-white font-bold py-2 px-4 rounded my-3">1年生全削除</button>
                                    <table class="table-fixed w-full border">
                                        <thead>
                                            <tr>
                                            <th class="px-4 py-2">受験番号</th>
                                            <th class="px-4 py-2 w-1/4 text-left">名前</th>
                                            <th class="px-4 py-2"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($users1 as $user1)
                                            <tr v-for="row in rows" class="bg-white odd:bg-gray-200">
                                                <td class="px-4 py-2 text-center">{{$user1->code}}</td>
                                                <td class="px-4 py-2">{{$user1->name}}</td>
                                                @foreach($user1->kadais as $kadai)
                                                <td class="px-4 py-2">{{$kadai->kadai_name}}：
                                                    {{$kadai->status}}
                                                </td>
                                                @endforeach
                                                <td>
                                                    {{$user1->memo}}
                                                </td>
                                                <td class="px-1 py-1 text-right">
                                                    <button wire:click="deleteStudentConfirm({{ $user1->code }}, '{{ $user1->name }}')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </p>
                            </div>
                            <div class="hidden" id="tab-two">
                                <p>
                                    <button wire:click="deleteStudentsConfirm(2)" class="bg-red-500 hover bg-red-700 text-white font-bold py-2 px-4 rounded my-3">2年生全削除</button>
                                    <table class="table-fixed w-full border">
                                        <thead>
                                            <tr>
                                            <th class="px-4 py-2">受験番号</th>
                                            <th class="px-4 py-2 w-1/4 text-left">名前</th>
                                            <th class="px-4 py-2"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($users2 as $user2)
                                            <tr v-for="row in rows" class="bg-white odd:bg-gray-200">
                                                <td class="px-4 py-2 text-center">{{$user2->code}}</td>
                                                <td class="px-4 py-2">{{$user2->name}}</td>
                                                @foreach($user2->kadais as $kadai)
                                                <td class="px-4 py-2">{{$kadai->kadai_name}}：
                                                    {{$kadai->status}}
                                                </td>
                                                @endforeach
                                                <td>
                                                    {{$user2->memo}}
                                                </td>
                                                <td class="px-1 py-1 text-right">
                                                    <button wire:click="deleteStudentConfirm({{ $user2->code }}, '{{ $user2->name }}')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </p>
                            </div>
                            <div class="hidden" id="tab-three">
                                <p>
                                    <button wire:click="deleteStudentsConfirm(3)" class="bg-red-500 hover bg-red-700 text-white font-bold py-2 px-4 rounded my-3">3年生全削除</button>
                                    <table class="table-fixed w-full border">
                                        <thead>
                                            <tr>
                                            <th class="px-4 py-2">受験番号</th>
                                            <th class="px-4 py-2 w-1/4 text-left">名前</th>
                                            <th class="px-4 py-2"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($users3 as $user3)
                                            <tr v-for="row in rows" class="bg-white odd:bg-gray-200">
                                                <td class="px-4 py-2 text-center">{{$user3->code}}</td>
                                                <td class="px-4 py-2">{{$user3->name}}</td>
                                                @foreach($user3->kadais as $kadai)
                                                <td class="px-4 py-2">{{$kadai->kadai_name}}：
                                                    {{$kadai->status}}
                                                </td>
                                                @endforeach
                                                <td>
                                                    {{$user3->memo}}
                                                </td>
                                                <td class="px-1 py-1 text-right">
                                                    <button wire:click="deleteStudentConfirm({{ $user3->code }}, '{{ $user3->name }}')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-2 rounded">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
<script type="text/javascript">
  function changeAtiveTab(event,tabID){
    let element = event.target;
    while(element.nodeName !== "A"){
      element = element.parentNode;
    }
    ulElement = element.parentNode.parentNode;
    aElements = ulElement.querySelectorAll("li > a");
    tabContents = document.getElementById("tabs-id").querySelectorAll(".tab-content > div");
    for(let i = 0 ; i < aElements.length; i++){
      aElements[i].classList.remove("text-white");
      aElements[i].classList.remove("bg-indigo-600");
      aElements[i].classList.add("text-indigo-600");
      aElements[i].classList.add("bg-white");
      tabContents[i].classList.add("hidden");
      tabContents[i].classList.remove("block");
    }
    element.classList.remove("text-indigo-600");
    element.classList.remove("bg-white");
    element.classList.add("text-white");
    element.classList.add("bg-indigo-600");
    document.getElementById(tabID).classList.remove("hidden");
    document.getElementById(tabID).classList.add("block");
  }
</script>