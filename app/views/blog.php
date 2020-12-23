<div class="content">
    <div class="row">
        <div class="col-lg-12 mt-2 mx-auto">

            <form method="post" enctype="multipart/form-data" action="/blog">
                <div class="form-group">
                    <label for="newsTitle">Title</label>
                    <input type="text" class="form-control" id="newsTitle" placeholder="Awesome Title" name="title">
                </div>
                <div class="form-group">
                    <label for="newContent">Content</label>
                    <textarea class="form-control" id="newContent" rows="15" name="content"></textarea>
                </div>
                <div class="form-group">
                    
                    <label for="newContent">Content Type</label>
                    <select class="form-control" id="contentType" name="type">
                        <option>Government</option>
                        <option>Food</option>
                        <option>Sports</option>
                        <option>Places</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <div class="custom-file">
                        <input class="custom-file-input" id="customFile" name="customFile" type="file">
                        <label class="custom-file-label" for="customFile">Choose file...</label>   
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <a class="btn btn-outline-danger" href="#">Cancel</a>
            </form>
        </div>
    </div>
</div>

