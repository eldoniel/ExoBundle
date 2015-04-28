function source() {
    jsPlumb.makeSource($(".origin"), {
        anchor: "Right",
        cssClass: "endPoints",
        isSource: true
    });
}

function target() {
    jsPlumb.makeTarget($(".droppable"), {
        anchor: "Left",
        cssClass: "endPoints",
        isTarget: true        
    });
}

function defaultParameters() {
    jsPlumb.importDefaults({
//        anchor: [ "Perimeter", { parent } ],
        ConnectionsDetachable:false,
        Connector: "Straight",
        DropOptions: {tolerance:"touch"},
        Endpoint: "Dot",
//        EndpointStyle: {fillStyle:"#777", radius: 5},
         EndpointStyle: [ [ "Dot", { radius:5 } ], [ "Dot", { fillStyle:"#777" } ] ],
        HoverPaintStyle: {strokeStyle:"red"},
        LogEnabled: false,
        PaintStyle: { strokeStyle:"#777", lineWidth: 4}
    });
}

function multiplesLinks() {
    jsPlumb.bind("beforeDrop", function(info){
        var connection = jsPlumb.getConnections({
            source:info["sourceId"],
            target:info["targetId"]
        });
        if(connection.length !== 0){
            //if the connection is already makes
            if (info["sourceId"] == connection[0].sourceId && info["targetId"] == connection[0].targetId) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    });
}

function removeConnections() {
    jsPlumb.bind("click", function(connection) {
        var target = connection["target"]["id"];
        var connectionsTarget = jsPlumb.getConnections({
            target:target
        });
        if (connectionsTarget.length > 1) {
            jsPlumb.detach(connection);
        } else {
            jsPlumb.detach(connection);
            jsPlumb.removeAllEndpoints($("#" + target));
        }
    });
}

function replaceConnections() {
    var connections = jsPlumb.getConnections();
    jsPlumb.detachEveryConnection();
    jsPlumb.unmakeEveryTarget();
    jsPlumb.unmakeEverySource();
    source();
    target();
    
    for(var i = 0; i < connections.length; i++) {
        jsPlumb.connect({source:connections[i].sourceId, target: connections[i].targetId});
    }
}