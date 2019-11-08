var Queue = function()
{
	this.head = null;
	this.length = 0;
	
	this.push = function(obj, key = 0)
	{
		var newNode = new Node(obj,key);
		
		if(this.head == null){
			this.head = newNode;
		}else{
			var curNode = this.head;
			var prevNode = null
			
			while(curNode != null && curNode.key >= key )
			{
				prevNode = curNode;
				curNode = curNode.next;
			}
			
			if(prevNode == null)
			{
				newNode.next = this.head;
				this.head = newNode;
			}else{
				newNode.next = curNode;
				prevNode.next = newNode;
			}
		}
		this.length++;
	}
	
	this.pop = function()
	{
		if(this.head == null) { return null; }
		
		var res = this.head;
		this.head = res.next;
		res.next = null;
		
		this.length--;
		return res.obj;
	}
	
	this.empty = function()
	{
		this.head = null;
		this.length = 0;
	}
	
	this.isEmpty = function()
	{
		return(this.head == null);
	}
	
	this.print = function()
	{
		var str = "";
		var node = this.head;
		for(var i = 0; i < this.length; i++)
		{
			str += node.key + " ";
			node = node.next;
		}
		console.log(str);
	}
}

var Node = function(obj,key)
{
	this.obj = obj;
	this.key = key;
	this.next = null;
}

var main = function()
{
	var vals = [1,5,2,4,8,7];
	
	var Q = new Queue();
	
	for(var i = 0; i < vals.length; i++)
	{
		Q.push(vals[i],vals[i]);
		Q.print();
	}
	
	console.log("Pushed!");
	
	console.log(Q);
	
	for(var i = 0; i < vals.length; i++)
	{
		var V = Q.pop();
		console.log( V);
	}
	
	console.log("Poped!");
}