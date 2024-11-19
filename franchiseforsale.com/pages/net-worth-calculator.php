
<div id="pg-directory">
    <div id="dirStates" class="net-worth-calc">
        <div class="states-block gray-border xy-padded">
                <div class="catTitle">
                    <p class="h3 white-text FrancoisOne">Calculate Your Net Worth</p>
                </div>
                <div class="gray-border white-bg desktop"><br>
                    <div class="assets pd-15 mb-1">
                        <div class="catTitle">
                            <p class="h3 white-text FrancoisOne">+ Assets</p>
                        </div>
                        <div class="gray-border white-bg">
                            <ul class="tabs hidden-sm" id="asset-tabs">
                                <li class="tabs-title is-active" onclick="netWorthTab(event,'assetPan1','assets');"><a href="#assetPan1" id="assetPan1-label">Checking / Savings</a></li>
                                <li class="tabs-title" onclick="netWorthTab(event,'assetPan2','assets');"><a href="#assetPan2">Stocks &amp; Bonds</a></li>
                                <li class="tabs-title" onclick="netWorthTab(event,'assetPan3','assets');"><a href="#assetPan3">Retirement Accounts</a></li>
                                <li class="tabs-title" onclick="netWorthTab(event,'assetPan4','assets');"><a href="#assetPan4">Personal</a></li>
                            </ul>
                            <div class="tabs-content">
                                <button type="button" class="hidden-md collapsible li-active">Checking / Savings</button>
                                <div class="tabs-panel show" id="assetPan1">
                                    <div class="tabs-row">
                                        <div class="input-block">
                                            <label>Checking/Savings:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="checkingSaving">
                                        </div>
                                        <div class="input-block">
                                            <label>Certificate of Deposit:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="certificateDeposit">
                                        </div>
                                        <div class="input-block">
                                            <label>Money Market Account:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="moneyMarket">
                                        </div>
                                        <div class="input-block last">&nbsp;</div>
                                    </div>
                                </div>
                                <button type="button" class="hidden-md collapsible">Stocks &amp; Bonds</button>
                                <div class="tabs-panel" id="assetPan2">
                                    <div class="tabs-row">
                                        <div class="input-block">
                                            <label>Stocks:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="stocks">
                                        </div>
                                        <div class="input-block">
                                            <label>Stock Options:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="stockOptions">
                                        </div>
                                        <div class="input-block">
                                            <label>Investment Real Estate:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="investmentRealEstate">
                                        </div>
                                        <div class="input-block last">&nbsp;</div>
                                    </div>
                                    <div class="tabs-row">
                                        <div class="input-block">
                                            <label>Treasury Bills:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="treasuryBills">
                                        </div>
                                        <div class="input-block">
                                            <label>Bonds:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="bonds">
                                        </div>
                                        <div class="input-block">
                                            <label>Mutual Funds:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="mutualFunds">
                                        </div>
                                        <div class="input-block last">&nbsp;</div>
                                    </div>
                                </div>
                                <button type="button" class="hidden-md collapsible">Retirement Accounts</button>
                                <div class="tabs-panel" id="assetPan3">
                                    <div class="tabs-row">
                                        <div class="input-block">
                                            <label>401k or 403b:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="401k">
                                        </div>
                                        <div class="input-block">
                                            <label>IRAs of Keogh:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="iraKoegh">
                                        </div>
                                        <div class="input-block">
                                            <label>Pensions:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="pensions">
                                        </div>
                                        <div class="input-block last">&nbsp;</div>
                                    </div>
                                </div>
                                <button type="button" class="hidden-md collapsible">Personal</button>
                                <div class="tabs-panel" id="assetPan4">
                                    <div class="tabs-row">
                                        <div class="input-block">
                                            <label>Value of Private Business:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="valuePrivateBusiness">
                                        </div>
                                        <div class="input-block">
                                            <label>Value of Life Insurance:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="valueLifeInsurance">
                                        </div>
                                        <div class="input-block">
                                            <label>Home:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="valueHome">
                                        </div>
                                        <div class="input-block last">&nbsp;</div>
                                    </div>
                                    <div class="tabs-row">
                                        <div class="input-block">
                                            <label>Car:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="valueCar">
                                        </div>
                                        <div class="input-block">
                                            <label>Other - Jewelry/Furnishings:</label> 
                                            <input class="form-controls asset" type="number" onchange="calculateFloat(this.id,'assets');" id="jewelleryFurnishing">
                                        </div>
                                        <div class="input-block last">&nbsp;</div>
                                        <div class="input-block last">&nbsp;</div>
                                    </div>
                                </div>
                            </div><br>
                        </div>
                    </div>
                    <!-- </div> -->
                    <div class="liabilities pd-15 mb-1">
                        <div class="catTitle">
                            <p class="h3 white-text FrancoisOne">- Liabilities</p>
                        </div>
                        <div class="gray-border white-bg">
                            <ul class="tabs hidden-sm" id="liability-tabs">
                                <li class="tabs-title is-active" onclick="netWorthTab(event,'liabPan1','liabilities');"><a href="#liabPan1" id="liabPan1-label">Credit Card Debt</a></li>
                                <li class="tabs-title" onclick="netWorthTab(event,'liabPan2','liabilities');"><a href="#liabPan2" id="liabPan2-label">Loans</a></li>
                                <li class="tabs-title" onclick="netWorthTab(event,'liabPan3','liabilities');"><a href="#liabPan3" id="liabPan3-label">Home Related Debt</a></li>
                                <li class="tabs-title" onclick="netWorthTab(event,'liabPan4','liabilities');"><a href="#liabPan4" id="liabPan4-label">Taxes</a></li>
                            </ul>
                            <div class="tabs-content">
                                <button type="button" class="hidden-md collapsible li-active">Credit Card Debt</button>
                                <div class="tabs-panel show" id="liabPan1">
                                    <div class="tabs-row">
                                        <div class="input-block">
                                            <label>Credit Card Balance:</label> 
                                            <input class="form-controls liability" type="number" onchange="calculateFloat(this.id,'liabilities');" id="creditCardBal">
                                        </div>
                                        <div class="input-block last">&nbsp;</div>
                                        <div class="input-block last">&nbsp;</div>
                                        <div class="input-block last">&nbsp;</div>
                                    </div>
                                </div>
                                <button type="button" class="hidden-md collapsible">Loans</button>
                                <div class="tabs-panel" id="liabPan2">
                                    <div class="tabs-row">
                                        <div class="input-block">
                                            <label>Car Loans:</label>  
                                            <input class="form-controls liability" type="number" onchange="calculateFloat(this.id,'liabilities');" id="carLoans">
                                        </div>
                                        <div class="input-block large-4">
                                            <label>Loans Against Investments or Insurance:</label> 
                                            <input class="form-controls liability" type="number" onchange="calculateFloat(this.id,'liabilities');" id="loanInvestment">
                                        </div>
                                        <div class="input-block">
                                            <label>Student Loans:</label> 
                                            <input class="form-controls liability" type="number" onchange="calculateFloat(this.id,'liabilities');" id="studentLoans">
                                        </div>
                                        <div class="input-block last">&nbsp;</div>
                                    </div>
                                </div>
                                <button type="button" class="hidden-md collapsible">Home Related Debt</button>
                                <div class="tabs-panel" id="liabPan3">
                                    <div class="tabs-row">
                                        <div class="input-block">
                                            <label>First Mortgage:</label> 
                                            <input class="form-controls liability" type="number" onchange="calculateFloat(this.id,'liabilities');" id="firstMortage">
                                        </div>
                                        <div class="input-block">
                                            <label>Second Mortgage:</label> 
                                            <input class="form-controls liability" type="number" onchange="calculateFloat(this.id,'liabilities');" id="secondMortage">
                                        </div>
                                        <div class="input-block">
                                            <label>Home Equity Line:</label> 
                                            <input class="form-controls liability" type="number" onchange="calculateFloat(this.id,'liabilities');" id="homeEquity">
                                        </div>
                                        <div class="input-block last">&nbsp;</div>
                                    </div>
                                </div>
                                <button type="button" class="hidden-md collapsible">Taxes</button>
                                <div class="tabs-panel" id="liabPan4">
                                    <div class="tabs-row">
                                        <div class="input-block">
                                            <label>Taxes Due:</label> 
                                            <input class="form-controls liability" type="number" onchange="calculateFloat(this.id,'liabilities');" id="taxesDue">
                                        </div>
                                        <div class="input-block">
                                            <label>Taxes - Investments (if cashed in):</label> 
                                            <input class="form-controls liability" type="number" onchange="calculateFloat(this.id,'liabilities');" id="taxesInvestment">
                                        </div>
                                        <div class="input-block">
                                            <label>Taxes - Retirement (if cashed in):</label> 
                                            <input class="form-controls liability" type="number" onchange="calculateFloat(this.id,'liabilities');" id="taxesRetirement">
                                        </div>
                                        <div class="input-block last">&nbsp;</div>
                                    </div>
                                </div>
                            </div><br>
                        </div>
                    </div>

                    <div class="pd-15 mb-1">
                        <div class="catTitle">
                            <p class="h3 white-text FrancoisOne">Total</p>
                        </div>
                        <div class="gray-border white-bg"><br>
                            <div class="input-block end">
                                <input maxlength="30" size="30" id="AssetTotal" value="0.00" name="AssetTotal" hidden>
                                <input maxlength="30" size="30" id="LiabilityTotal" value="0.00" name="LiabilityTotal" hidden>
                                <label class="bold">Your Net Worth:</label> 
                                <input class="form-controls" type="text" id="netWorthTotal">
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<?php load_js('calculator'); ?>