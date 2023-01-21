<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SecurityCodesController extends Controller
{
    public function updateForm(): View
    {
        return view('code.update');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validateWithBag('codeGeneration', [
            'password' => ['required', 'current-password'],
        ]);

        $newCodes = new Collection();

        foreach (auth()->user()->securityCodes()->get() as $securityCode) {
            $newCode = Str::random(8);
            $securityCode->fill(['code' => Hash::make($newCode)])
                ->save();
            $newCodes->add($newCode);
        }

        return redirect('/codes')->with([
            'status' => 'codes-generated',
            'securityCodes' => $newCodes,
        ]);
    }

    public function index(Request $request): View
    {
        $securityCodes = $request->session()->pull('securityCodes');
        $timeUpdatedAt = auth()->user()->securityCodes()->first()->updatedAtFormatted;

        return view('code.index')->with([
            'securityCodes' => $securityCodes,
            'timeUpdatedAt' => $timeUpdatedAt,
        ]);
    }
}
