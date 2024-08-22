<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Evaluation</title>
    <link rel="stylesheet" href="../Css/Inventory/suppliers.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="#">Inventory catalog</a></li>
                <li><a href="#">Purchase orders</a></li>
                <li><a href="#">Sales orders</a></li>
                <li><a href="#">Package</a></li>
                <li><a href="#">Shipment</a></li>
                <li><a href="#">Reports</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
        </nav>
        <div class="search-bar">
            <input type="text" placeholder="Search">
        </div>
    </header>
    <main>
        <section class="supplier-info">
            <h1>Suppliers > Wells & Wade</h1>
            <div class="supplier-details">
                <h3>About Supplier</h3>
                <div class="supplier-name">Wells & Wade</div>
                <div class="supplier-address">Address: Ohio, Delaware, 435 Park Ave</div>
                <div class="supplier-phone">Phone: 614-205-9702</div>
                <div class="supplier-contact">Contact person: Michael Ripley, Inventory Manager</div>
                <div class="supplier-partner">Partners since: August 2017</div>
            </div>
            <div class="recent-deals">
                <h3>Recent Deals</h3>
                <div class="deals-header">
                    <div class="deal-order">Order #</div>
                    <div class="deal-created">Created at</div>
                    <div class="deal-amount">Amount</div>
                </div>
                <div class="deal-item">
                    <div class="deal-order">2384</div>
                    <div class="deal-created">08/18/2021, 1:08 PM</div>
                    <div class="deal-amount">$175,975.00</div>
                </div>
                <div class="deal-item">
                    <div class="deal-order">2341</div>
                    <div class="deal-created">07/12/2021, 10:54 AM</div>
                    <div class="deal-amount">$74,340.00</div>
                </div>
                <div class="deal-item">
                    <div class="deal-order">2304</div>
                    <div class="deal-created">06/04/2021, 4:35 PM</div>
                    <div class="deal-amount">$90,200.00</div>
                </div>
                <a href="#" class="more-deals">More Deals ></a>
            </div>
        </section>
        <section class="supplier-evaluation">
            <h3>Supplier Evaluation</h3>
            <div class="evaluation-table">
                <div class="evaluation-header">
                    <div class="evaluation-category">Competency</div>
                    <div class="evaluation-rating">1 - Poor</div>
                    <div class="evaluation-rating">2 - Fair</div>
                    <div class="evaluation-rating">3 - Satisfactory</div>
                    <div class="evaluation-rating">4 - Good</div>
                    <div class="evaluation-rating">5 - Excellent</div>
                </div>
                <div class="evaluation-row">
                    <div class="evaluation-category">Capacity</div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox" checked></div>
                </div>
                <div class="evaluation-row">
                    <div class="evaluation-category">Commitment</div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox" checked></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                </div>
                <div class="evaluation-row">
                    <div class="evaluation-category">Control</div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox" checked></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                </div>
                <div class="evaluation-row">
                    <div class="evaluation-category">Cost</div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox" checked></div>
                </div>
                <div class="evaluation-row">
                    <div class="evaluation-category">Consistency</div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox" checked></div>
                </div>
                <div class="evaluation-row">
                    <div class="evaluation-category">Culture</div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox" checked></div>
                </div>
                <div class="evaluation-row">
                    <div class="evaluation-category">Communication</div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox"></div>
                    <div class="evaluation-rating"><input type="checkbox" checked></div>
                </div>
            </div>
            <div class="evaluation-summary">
                <div class="risk-assessment">Risk Assessment: <span class="risk-status">Low</span></div>
                <div class="overall-rating">Overall rating: 4.75</div>
            </div>
            <button class="save-button">Save</button>
        </section>
    </main>
    <footer>
        
    </footer>
</body>
</html>